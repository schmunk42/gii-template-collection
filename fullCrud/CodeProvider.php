<?php

class CodeProvider {

	public function generateDateField () {
			
	}

		public function resolveController($relation) {
		$model = new $relation[1];
		$reflection = new ReflectionClass($model);
		$module = preg_match("/\/modules\/([a-zA-Z0-9]+)\//", $reflection->getFileName(), $matches);
		$modulePrefix = (isset($matches[$module]))?"/".$matches[$module]."/":"/";
		$controller = $modulePrefix.strtolower(substr($relation[1],0,1)).substr($relation[1],1);
		return $controller;
	}
	
	static public function generateActiveField($model, $column) {
		if (strtoupper($column->dbType) == 'TINYINT(1)'
				|| strtoupper($column->dbType) == 'BIT'
				|| strtoupper($column->dbType) == 'BOOL'
				|| strtoupper($column->dbType) == 'BOOLEAN') {
			return "echo \$form->checkBox(\$model,'{$column->name}')";
		} else if (strtoupper($column->dbType) == 'DATE') {
			$modelname = get_class($model);
		} else if (substr(strtoupper($column->dbType), 0, 4) == 'ENUM') {
			$string = sprintf("echo CHtml::activeDropDownList(\$model, '%s', array(\n", $column->name);

			$enum_values = explode(',', substr($column->dbType, 4, strlen($column->dbType) - 1));

			foreach ($enum_values as $value) {
				$value = trim($value, "()'");
				$string .= "\t\t\t'$value' => Yii::t('app', '" . $value . "') ,\n";
			}
			$string .= '))';

			return $string;
		} else {
			return null;
		}
	}

	public function generateRelation($model, $relationname, $relation) {
		// Use the second attribute of the model, since the first is the id in
		// most cases
		// 
		// TODO: remove code, done via _label
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
					return "if (\$model->{$relationname} !== null) echo \$model->{$relationname}->_label;";
				}
				else
					$allowEmpty= (CActiveRecord::model($model)->tableSchema->columns[$relation[2]]->allowNull?'true':'false');

				return("\$this->widget(
					'Relation',
					array(
							'model' => \$model,
							'relation' => '{$relationname}',
							'fields' => '_label',
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
/*	public function generateActiveField($model, $column) {
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
	} */

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

			if (method_exists($fmodel,'get_label')) {
				$fcolumns[1] = "_label";
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
