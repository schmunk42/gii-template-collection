<?php

Yii::import('system.gii.generators.crud.CrudCode');
Yii::import('ext.gtc.components.*');
Yii::import('ext.gtc.fullCrud.CodeProvider');

class FullCrudCode extends CrudCode {
	// validation method; 0 = none, 1 = ajax, 2 = client-side, 3 = both
	public $validation = 3;
	public $baseControllerClass='Controller';
	public $codeProvider;

	public function prepare() {
		$this->codeProvider = new CodeProvider;
		parent::prepare();
	}

	public function rules()                                                       
	{
		return array_merge(parent::rules(), array(
					array('validation', 'required'),
					));
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
