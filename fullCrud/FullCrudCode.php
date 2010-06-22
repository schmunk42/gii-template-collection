<?php
Yii::import('system.gii.generators.crud.CrudCode');

class FullCrudCode extends CrudCode
{

	public function prepare()
	{
		parent::prepare();

		// Make sure that the Relation Widget is in the application components
		// Folder. if it is not, copy it over there

		$path = Yii::getPathOfAlias('application.components');
		if($path===false)
			mkdir($path);

		if(!is_dir($path))
			throw new CException('Fatal Error: Your application components/ is not an directory!');	

		$names = scandir($path);

		if(!in_array('Relation.php', $names)) 
		{
			$gtcpath = Yii::getPathOfAlias('ext.gtc.vendors');
			if(!copy($gtcpath.'/Relation.php', $path.'/Relation.php'))
				throw new CException('Relation.php could not be copied over to your components directory');
		}

	}

	public function suggestName($columns) 
	{
		$j = 0;
		foreach($columns as $column) 
		{
			if(!$column->isForeignKey 
					&& ! $column->isPrimaryKey 
					&& $column->type != 'INT' 
					&& $column->type != 'INTEGER' 
					&& $column->type != 'BOOLEAN') {
				$num = $j;
				break;
			}
			$j++;
		}

		for($i = 0; $i < $j; $i++)
			next($columns);

		if(is_object(current($columns)))
			return current($columns);
		else {
			$column = reset($columns);
			return $column;
		}
	}

	public function getRelations()
	{
		return CActiveRecord::model($this->model)->relations();
	}

	public function generateRelation($model, $relationname, $relation)
	{
		// Use the second attribute of the model, since the first is the id in
		// most cases
		if($columns = CActiveRecord::model($relation[1])->tableSchema->columns)
		{
			$j = 0;
			foreach($columns as $column) 
			{
				if(!$column->isForeignKey && ! $column->isPrimaryKey) {
					$num = $j;
					break;
				}
				$j++;
			}

			for($i = 0; $i < $j; $i++)
				next($columns);

			$field = current($columns);
			$is_empty = $field->allowNull ? 'true' : 'false';
			$style = $relation[0] == 'CManyManyRelation' ? 'checkbox' : 'dropdownlist';

			return("
					\$this->widget('application.components.Relation', array(
							'model' => \$model,
							'relation' => '{$relationname}',
							'fields' => '{$field->name}',
							'allowEmpty' => $is_empty,
							'style' => '{$style}',
							)
						)");
		}
	}

	/**
	 * @param CActiveRecord $modelClass
	 * @param CDbColumnSchema $column
	 */
	public function generateActiveField($model, $column) 
	{
		if(!is_object($model))	
			$model = CActiveRecord::model($model);

		if($column->isForeignKey) 
			return false;

		if(strtoupper($column->dbType) == 'TINYINT(1)' 
				|| strtoupper($column->dbType) == 'BIT'
				|| strtoupper($column->dbType) == 'BOOL'
				|| strtoupper($column->dbType) == 'BOOLEAN') 
		{
			return "echo \$form->checkBox(\$model,'{$column->name}')";
		}
		else if(strtoupper($column->dbType) == 'DATE') 
		{
			$modelname = get_class($model);
			return ("\$this->widget('zii.widgets.jui.CJuiDatePicker',
						 array(
								 'model'=>'\$model',
								 'name'=>'{$modelname}[{$column->name}]',
								 //'language'=>'de',
								 'value'=>\$model->{$column->name},
								 'htmlOptions'=>array('size'=>10, 'style'=>'width:80px !important'),
									 'options'=>array(
									 'showButtonPanel'=>true,
									 'changeYear'=>true,                                      
									 'changeYear'=>true,
									 ),
								 )
							 );
					");
		}
		else 
		{
			return('echo '.parent::generateActiveField($model, $column));  
		}
	}


	/**
	 * @param CActiveRecord $modelClass
	 * @param CDbColumnSchema $column
	 */
	public function generateValueField($modelClass, $column, $view = false) {
		if($column->isForeignKey) 
		{
			$model=CActiveRecord::model($modelClass);
			$table=$model->getTableSchema();
			$fk = $table->foreignKeys[$column->name];
			$fmodel=CActiveRecord::model(ucfirst($fk[0]));
			$modelTable = ucfirst($fmodel->tableName());
			$fcolumns=$fmodel->attributeNames();
			//$rel = $model->getActiveRelation($column->name);
			$relname = strtolower($fk[0]);
			foreach($model->relations() as $key => $value) 
			{
				if(strcasecmp($value[2], $column->name) == 0)
					$relname = $key;
			}
			//return("\$model->{$relname}->{$fcolumns[1]}");
			//return("CHtml::value(\$model,\"{$relname}.{$fcolumns[1]}\")");
			//return("{$relname}.{$fcolumns[1]}");
			if($view) {
				return "
					array(
							'name'=>'{$column->name}',
							'value'=>CHtml::value(\$model,'{$relname}.{$fcolumns[1]}'),
							)
					";
			} else
				return  "
					array(
							'name'=>'{$column->name}',
							'value'=>'\$data->{$relname}->{$fcolumns[1]}',
							'filter'=>CHtml::listData({$modelTable}::model()->findAll(), '{$fcolumns[0]}', '{$fcolumns[1]}'),
							)
					";
			//{$relname}.{$fcolumns[1]}
		}
		elseif (strtoupper($column->dbType)=='BOOLEAN' or strtoupper($column->dbType) == 'TINYINT(1)' OR strtoupper($column->dbType) == 'BIT') {

			if($view) {
				return "
					array(
							'name'=>'{$column->name}',
							'value'=>\$model->{$column->name}?Yii::t('app', 'Yes'):Yii::t('app', 'No'),
							)
					";
			} else
				return "
					array(
							'name'=>'{$column->name}',
							'value'=>'\$data->{$column->name}?Yii::t('app','Yes'):Yii::t('app', 'No')',
								'filter'=>array('0'=>Yii::t('app','No'),'1'=>Yii::t('app','Yes')),
								)
							";
							}
							else {
							return("'".$column->name."'");
							}
							}

							public function guessNameColumn($columns)
							{
							$name = Yii::t('app','name');

							foreach($columns as $column)
							{
							if(!strcasecmp($column->name,$name))
							return $column->name;
							}

							$title = Yii::t('app','title');

							foreach($columns as $column)
							{
								if(!strcasecmp($column->name,$title))
									return $column->name;
							}

							foreach($columns as $column)
							{
								if($column->isPrimaryKey)
									return $column->name;
							}
							return 'id';
							}
}
?>
