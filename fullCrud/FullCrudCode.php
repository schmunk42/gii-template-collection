<?php

Yii::import('system.gii.generators.crud.CrudCode');

class FullCrudCode extends CrudCode {
	public $authtype;
	public $persistent_sessions = true;
	public $enable_ajax_validation = true;

	public function prepare() {
		$this->baseControllerClass = 'GController';
		parent::prepare();
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
		parent::init();

		// Make sure that the Relation Widget is in the application components
		// Folder. if it is not, copy it over there

		$extensionspath = Yii::getPathOfAlias('application.extensions');
		$controllerspath = Yii::getPathOfAlias('application.controllers');

		if ($extensionspath === false)
			mkdir($extensionspath);

		if ($controllerspath === false)
			mkdir($controllerspath);

		if (!is_dir($extensionspath))
			throw new CException('Fatal Error: Your application extensions/ is not an directory!');

		if (!is_dir($controllerspath))
			throw new CException('Fatal Error: Your application controllers/ is not an directory!');


		$names = scandir($extensionspath);
		$gtcpath = Yii::getPathOfAlias('ext.gtc.vendors');

		if (!in_array('Relation.php', $names)) {
			if (!copy($gtcpath . '/Relation.php', $extensionspath . '/Relation.php'))
				throw new CException('Relation.php could not be copied over to your components directory');
		}

		$names = scandir($controllerspath);

		if (!in_array('GController.php', $names)) {
			if (!copy($gtcpath . '/GController.php',
						$controllerspath . '/GController.php'))
				throw new CException('GController.php could not be copied over to your components directory');
		}
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
				if($relation[0] == 'CManyManyRelation')
					$allowEmpty='false';
				else
					$allowEmpty= (CActiveRecord::model($model)->tableSchema->columns[$relation[2]]->allowNull?'true':'false');
				
				return("
						\$this->widget('ext.Relation', array(
								'model' => \$model,
								'relation' => '{$relationname}',
								'fields' => '{$field->name}',
								'allowEmpty' => {$allowEmpty},
								'style' => '{$style}',
								'htmlOptions' => array(
									'checkAll' => Yii::t('app', 'Choose all'),
									),

								)
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

		/*if ($column->isForeignKey)
			return false;*/

		if (strtoupper($column->dbType) == 'TINYINT(1)'
				|| strtoupper($column->dbType) == 'BIT'
				|| strtoupper($column->dbType) == 'BOOL'
				|| strtoupper($column->dbType) == 'BOOLEAN') {
			return "echo \$form->checkBox(\$model,'{$column->name}')";
		} else if (strtoupper($column->dbType) == 'DATE') {
			$modelname = get_class($model);
			return ("\$this->widget('zii.widgets.jui.CJuiDatePicker',
        array(
            'model'=>'\$model',
            'name'=>'{$modelname}[{$column->name}]',
            'language'=>Yii::app()->language,
            'value'=>\$model->{$column->name},
            'htmlOptions'=>array('size'=>10, 'style'=>'width:80px !important'),
            'options'=>array(
                'showButtonPanel'=>true,
                'changeYear'=>true,
                'changeYear'=>true,
                'dateFormat'=>'yy-mm-dd',
                ),
            )
        );
");
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
					echo $relation = $value;
			}
                        $fmodel = CActiveRecord::model($relation[1]);
			#$fmodel = CActiveRecord::model(ucfirst($fk[0]));

			$modelTable = ucfirst($fmodel->tableName());
			$fcolumns = $fmodel->attributeNames();
			$relname = strtolower($fk[0]);

			foreach ($model->relations() as $key => $value) {
				if (strcasecmp($value[2], $column->name) == 0)
					$relname = $key;
			}
			//return("\$model->{$relname}->{$fcolumns[1]}");
			//return("CHtml::value(\$model,\"{$relname}.{$fcolumns[1]}\")");
			//return("{$relname}.{$fcolumns[1]}");
			if ($view===true) {
				return "
					array(
							'name'=>'{$column->name}',
							'value'=>CHtml::value(\$model,'{$relname}.{$fcolumns[1]}'),
							)";
			} elseif($view=='search')
			return "\$form->dropDownList(\$model,'{$column->name}',CHtml::listData({$modelTable}::model()->findAll(), '{$fmodel->getTableSchema()->primaryKey}', '{$fcolumns[1]}'),array('prompt'=>Yii::t('app', 'All')))";
			else
				return "
					array(
							'name'=>'{$column->name}',
							'value'=>'CHtml::value(\$data,\\'{$relname}.{$fcolumns[1]}\\')',
								'filter'=>CHtml::listData({$modelTable}::model()->findAll(), '{$fcolumns[0]}', '{$fcolumns[1]}'),
								)";
							//{$relname}.{$fcolumns[1]}
							}
							elseif (strtoupper($column->dbType) == 'BOOLEAN' or strtoupper($column->dbType) == 'TINYINT(1)' OR strtoupper($column->dbType) == 'BIT') {

							if ($view) {
							return "
							array(
								'name'=>'{$column->name}',
								'value'=>\$model->{$column->name}?Yii::t('app', 'Yes'):Yii::t('app', 'No'),
								)";
							} else
							return "
							array(
								'name'=>'{$column->name}',
								'value'=>'\$data->{$column->name}?Yii::t(\\'app\\',\\'Yes\\'):Yii::t(\\'app\\', \\'No\\')',
									'filter'=>array('0'=>Yii::t('app','No'),'1'=>Yii::t('app','Yes')),
									)";
								}
								else {
								return("'" . $column->name . "'");
								}
								}

								public function guessNameColumn($columns) {
								$name = Yii::t('app', 'name');

								foreach ($columns as $column) {
								if (!strcasecmp($column->name, $name))
								return $column->name;
								}

								$title = Yii::t('app', 'title');

								foreach ($columns as $column) {
									if (!strcasecmp($column->name, $title))
										return $column->name;
								}

								foreach ($columns as $column) {
									if ($column->isPrimaryKey)
										return $column->name;
								}
								return 'id';
								}

}
?>
