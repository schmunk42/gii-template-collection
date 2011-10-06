<?php

Yii::import('system.gii.generators.crud.CrudCode');
Yii::import('ext.gtc.components.*');
Yii::import('ext.gtc.fullCrud.CodeProvider');

class FullCrudCode extends CrudCode {
	// validation method; 0 = none, 1 = ajax, 2 = client-side, 3 = both
	public $validation = 3;
	public $identificationColumn = null;
	public $baseControllerClass='Controller';
	public $codeProvider;

	public function prepare() {
		$this->codeProvider = new CodeProvider;
		if(!$this->identificationColumn)
			$this->identificationColumn = $this->tableSchema->primaryKey;

		if(!array_key_exists(
					$this->identificationColumn, $this->tableSchema->columns))
			$this->addError('identificationColumn', 'The specified column can not be found in the models attributes. <br /> Please specify a valid attribute. If unsure, leave the field empty.'); 
		parent::prepare();
	}

	public function rules()                                                       
	{
		return array_merge(parent::rules(), array(
					array('validation', 'required'),
					array('identificationColumn', 'safe'),
					));
	}

	public function validateModel($attribute,$params)
	{
		// check your import paths, if you get an error here
		// PHP error can't be catched as an exception
		if($this->model) 
			Yii::import($this->model, true);
		parent::validateModel($attribute,$params);
	}
	
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
					'validation'=>'Validation method',
					));
	}

	public function init() {
		parent::init();
	}

	// Which column will most probably be the one that gets used to list
	// a model ? It may be the first non-numeric column.
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

	
}

?>
