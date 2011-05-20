<?php
Yii::import('system.gii.generators.model.ModelCode');
Yii::import('ext.gtc.components.*');

class FullModelCode extends ModelCode {
	public $baseClass = 'GActiveRecord';

	public function init() {
		parent::init();

		if (!@class_exists("CSaveRelationsBehavior")) {
			throw new CException("Fatal Error: Class 'CSaveRelationsBehavior' could not be found in your application! Add 'ext.gtc.components.*' to your import paths.");
		}
		if (!@class_exists("GActiveRecord")) {
			throw new CException("Fatal Error: Class 'GActiveRecord' could not be found in your application! Add 'ext.gtc.components.*' to your import paths.");
		}

	}

	public function prepare() {
		parent::prepare();

		$templatePath = $this->templatePath;

		if (($pos = strrpos($this->tableName, '.')) !== false) {
			$schema = substr($this->tableName, 0, $pos);
			$tableName = substr($this->tableName, $pos + 1);
		} else {
			$schema = '';
			$tableName = $this->tableName;
		}
		if ($tableName[strlen($tableName) - 1] === '*') {
			$tables = Yii::app()->db->schema->getTables($schema);
			if ($this->tablePrefix != '') {
				foreach ($tables as $i => $table) {
					if (strpos($table->name, $this->tablePrefix) !== 0)
						unset($tables[$i]);
				}
			}
		}
		else
			$tables=array($this->getTableSchema($this->tableName));

		$this->relations = $this->generateRelations();

		foreach ($tables as $table) {
			$tableName = $this->removePrefix($table->name);
			$className = $this->generateClassName($table->name);
			$params = array(
					'tableName' => $schema === '' ? $tableName : $schema . '.' . $tableName,
					'modelClass' => $className,
					'columns' => $table->columns,
					'labels' => $this->generateLabels($table),
					'rules' => $this->generateRules($table),
					'relations' => isset($this->relations[$className]) ? $this->relations[$className] : array(),
					);

		if($this->template != 'singlefile')
			$this->files[] = new CCodeFile(
					Yii::getPathOfAlias($this->modelPath) . '/' . 'Base' . $className . '.php',
					$this->render($templatePath . '/basemodel.php', $params)
					);
		}
	}

	public function requiredTemplates() {
		if($this->template == 'singlefile')
			return array('model.php');
		else
			return array(
					'model.php',
					'basemodel.php',
					);
	}

	public function generateRules($table)
	{
		$rules=array();
		$required=array();
		$null=array();
		$integers=array();
		$numerical=array();
		$length=array();
		$safe=array();
		foreach($table->columns as $column)
		{
			if($column->isPrimaryKey && $table->sequenceName!==null)
				continue;
			$r=!$column->allowNull && $column->defaultValue===null;
			if($r)
				$required[]=$column->name;
			else
				$null[]=$column->name;
			if($column->type==='integer')
				$integers[]=$column->name;
			else if($column->type==='double')
				$numerical[]=$column->name;
			else if($column->type==='string' && $column->size>0)
				$length[$column->size][]=$column->name;
			else if(!$column->isPrimaryKey && !$r)
				$safe[]=$column->name;
		}
		if($required!==array())
			$rules[]="array('".implode(', ',$required)."', 'required')";
		if($null!==array())
			$rules[]="array('".implode(', ',$null)."', 'default', 'setOnEmpty' => true, 'value' => null)";
		if($integers!==array())
			$rules[]="array('".implode(', ',$integers)."', 'numerical', 'integerOnly'=>true)";
		if($numerical!==array())
			$rules[]="array('".implode(', ',$numerical)."', 'numerical')";
		if($length!==array())
		{
			foreach($length as $len=>$cols)
				$rules[]="array('".implode(', ',$cols)."', 'length', 'max'=>$len)";
		}
		if($safe!==array())
			$rules[]="array('".implode(', ',$safe)."', 'safe')";

		return $rules;
	}

}

?>
