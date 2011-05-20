<?php

Yii::import('system.gii.generators.crud.CrudCode');
Yii::import('ext.gtc.components.*');

class FullCrudCode extends CrudCode {
	public $authtype;
	public $persistent_sessions = true;
	public $enable_ajax_validation = true;
	public $baseControllerClass='GController';

	public function prepare() {
		parent::prepare();
		if(!isset($this->baseControllerClass))
			$this->baseControllerClass;
	}

	public function rules()                                                       
	{
		return array_merge(parent::rules(), array(
					array('authtype, persistent_sessions, enable_ajax_validation', 'required'),
					));
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
					'authtype'=>'Authentication type',
					'persistent_sessions'=>'Persistent Sessions',
					'enable_ajax_validateion'=>'Enable ajax Validation',
					));
	}

	public function init() {
		// just check if the classes can be found
		if (!@class_exists("GController")) {
			throw new CException("Fatal Error: Class 'GController' could not be found in your application! Add 'ext.gtc.components.*' to your import paths.");
		}
		if (!@class_exists("Relation")) {
			throw new CException("Fatal Error: Class 'Relation' could not be found in your application! Add 'ext.gtc.components.*' to your import paths.");
		}
		parent::init();
	}

	// suggest which database column is best suited for being display in
	// a foreign Relation
	public function suggestName($columns) {
		$j = 0;
		foreach ($columns as $column) {
			if (!$column->isForeignKey
					&& !$column->isPrimaryKey
					&& $column->type != 'INT'
					&& $column->type != 'INTEGER'
					&& $column->type != 'BOOLEAN') {
				$num = $j;
				break;
			}
			$j++;
		}

		for ($i = 0; $i < $j; $i++)
			next($columns);

		if (is_object(current($columns)))
			return current($columns);
		else {
			$column = reset($columns);
			return $column;
		}
	}

	public function getRelations() {
		return CActiveRecord::model($this->modelClass)->relations();
	}

	public function generateRelation($model, $relationname, $relation) {
		// Use the second attribute of the model, since the first is the id in
		// most cases
		if ($columns = CActiveRecord::model($relation[1])->tableSchema->columns) {
			$j = 0;
			foreach ($columns as $column) {
				if (!$column->isForeignKey && !$column->isPrimaryKey) {
					$num = $j;
					break;
				}
				$j++;
			}

			for ($i = 0; $i < $j; $i++)
				next($columns);

			$field = current($columns);
			$style = $relation[0] == 'CManyManyRelation' ? 'checkbox' : 'dropdownlist';

			if (is_object($field)) {
				if ($relation[0] == 'CManyManyRelation')
					$allowEmpty = 'false';
				elseif ($relation[0] == 'CHasOneRelation') {
					$allowEmpty = (CActiveRecord::model($relation[1])->tableSchema->columns[$relation[2]]->allowNull ? 'true' : 'false');
					return "if (\$model->{$relationname} !== null) echo \$model->{$relationname}->title;";
				}
				else
					$allowEmpty= (CActiveRecord::model($model)->tableSchema->columns[$relation[2]]->allowNull?'true':'false');

				return("\$this->widget(
					'Relation',
					array(
							'model' => \$model,
							'relation' => '{$relationname}',
							'fields' => '{$field->name}',
							'allowEmpty' => {$allowEmpty},
							'style' => '{$style}',
							'htmlOptions' => array(
								'checkAll' => Yii::t('app', 'Choose all'),
								),)
						)");
			}
		}
	}

	/**
	 * @param CActiveRecord $modelClass
	 * @param CDbColumnSchema $column
	 */
	public function generateActiveField($model, $column) {
		if (!is_object($model))
			$model = CActiveRecord::model($model);

		$providerPaths = Yii::app()->controller->module->params['gtc.fullCrud.providers'];
		$providerPaths[] = 'ext.gtc.fullCrud.providers.FullCrudFieldProvider';

		$field = null;
		foreach($providerPaths AS $provider) {
			$providerClass = Yii::createComponent($provider);
			if (($field = $providerClass::generateActiveField($model, $column)) !== null)
				break;
		}

		if ($field !== null) {
			return $field;
		} else {
			return('echo ' . parent::generateActiveField($model, $column));
		}
	}

	/**
	 * @param CActiveRecord $modelClass
	 * @param CDbColumnSchema $column
	 */
	public function generateValueField($modelClass, $column, $view = false) {
		if ($column->isForeignKey) {

			$model = CActiveRecord::model($modelClass);
			$table = $model->getTableSchema();
			$fk = $table->foreignKeys[$column->name];

			// We have to look into relations to find the correct model class (i.e. if models are generated with table prefix)
			// TODO: do not repeat yourself (foreach) - this is a hotfix
			foreach ($model->relations() as $key => $value) {
				if (strcasecmp($value[2], $column->name) == 0)
					$relation = $value;
			}
			$fmodel = CActiveRecord::model($relation[1]);
			$fmodelName = $relation[1];

			$modelTable = ucfirst($fmodel->tableName());
			$fcolumns = $fmodel->attributeNames();

			if (method_exists($fmodel,'getRecordTitle')) {
				$fcolumns[1] = "recordTitle";
			}

			//$rel = $model->getActiveRelation($column->name);
			$relname = strtolower($fk[0]);
			foreach ($model->relations() as $key => $value) {
				if (strcasecmp($value[2], $column->name) == 0)
					$relname = $key;
			}
			//return("\$model->{$relname}->{$fcolumns[1]}");
			//return("CHtml::value(\$model,\"{$relname}.{$fcolumns[1]}\")");
			//return("{$relname}.{$fcolumns[1]}");
			if ($view === true) {
				return "array(
					'name'=>'{$column->name}',
					'value'=>CHtml::value(\$model,'{$relname}.{$fcolumns[1]}'),
					)";
			} elseif ($view == 'search')
			return "\$form->dropDownList(\$model,'{$column->name}',CHtml::listData({$fmodelName}::model()->findAll(), '{$fmodel->getTableSchema()->primaryKey}', '{$fcolumns[1]}'),array('prompt'=>Yii::t('app', 'All')))";
			else
				return "array(
					'name'=>'{$column->name}',
					'value'=>'CHtml::value(\$data,\\'{$relname}.{$fcolumns[1]}\\')',
							'filter'=>CHtml::listData({$fmodelName}::model()->findAll(), '{$fcolumns[0]}', '{$fcolumns[1]}'),
							)";
			//{$relname}.{$fcolumns[1]}
		} else if (strtoupper($column->dbType) == 'BOOLEAN' 
				or strtoupper($column->dbType) == 'TINYINT(1)' or
				strtoupper($column->dbType) == 'BIT') {
			if ($view) {
				return "array(
					'name'=>'{$column->name}',
					'value'=>\$model->{$column->name}?Yii::t('app', 'Yes'):Yii::t('app', 'No'),
					)";
			} else
				return "array(
					'name'=>'{$column->name}',
					'value'=>'\$data->{$column->name}?Yii::t(\\'app\\',\\'Yes\\'):Yii::t(\\'app\\', \\'No\\')',
							'filter'=>array('0'=>Yii::t('app','No'),'1'=>Yii::t('app','Yes')),
							)";
		} else if($column->name == 'createtime'
				or $column->name == 'updatetime'
				or $column->name == 'timestamp')
		{
			return "array(
				'name'=>'{$column->name}',
				'value' =>'date(\"Y. m. d G:i:s\", \$data->{$column->name})')";
		} else {
			return("'" . $column->name . "'");
		}
	}
}

?>
