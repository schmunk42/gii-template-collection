<?php
Yii::import('system.gii.generators.model.ModelCode');
Yii::import('ext.gtc.components.*');

class FullModelCode extends ModelCode {
	public $tables;
	public $baseClass = 'CActiveRecord';
	public $identificationColumn = null;

	public function init() {
		parent::init();

		if (!@class_exists("CSaveRelationsBehavior")) {
			throw new CException("Fatal Error: Class 'CSaveRelationsBehavior' could not be found in your application! Add 'ext.gtc.components.*' to your import paths.");
		}

	}

	public function rules()                                                       
	{
		return array_merge(parent::rules(), array(
					array('identificationColumn', 'safe'),
					));
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
			$this->tables = Yii::app()->db->schema->getTables($schema);
			if ($this->tablePrefix != '') {
				foreach ($this->tables as $i => $table) {
					if (strpos($table->name, $this->tablePrefix) !== 0)
						unset($this->tables[$i]);
				}
			}
		}
		else
			$this->tables=array($this->getTableSchema($this->tableName));

		$this->relations = $this->generateRelations();

		foreach ($this->tables as $table) {
			$tableName = $this->removePrefix($table->name);
			$className = $this->generateClassName($table->name);

			if(!$this->identificationColumn)
				$this->identificationColumn = $this->guessIdentificationColumn(
						$table->columns);

		if(!array_key_exists(
					$this->identificationColumn, $table->columns))
			$this->addError('identificationColumn', 'The specified column can not be found in the models attributes. <br /> Please specify a valid attribute. If unsure, leave the field empty.'); 


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

	public function getBehaviors($columns) {
			$behaviors = 'return array(';
					if(count($this->relations) > 0)
					$behaviors .= "'CSaveRelationsBehavior', array(
				'class' => 'CSaveRelationsBehavior'),";

					foreach($columns as $name => $column) {
					if(in_array($column->name, array(
								'create_time',
								'createtime',
								'created_at',
								'createdat',
								'changed',
								'changed_at',
								'updatetime',
								'update_time',
								'timestamp'))) {
					$behaviors .= sprintf("\n\t\t'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => %s,
				'updateAttribute' => %s,
				\t),\n", $this->getCreatetimeAttribute($columns),
						$this->getUpdatetimeAttribute($columns));
					break; // once a column is found, we are done
					}
					}
					foreach($columns as $name => $column) {
						if(in_array($column->name, array(
										'user_id',
										'userid',
										'ownerid',
										'owner_id',
										'created_by',
										'createdby'))) {
							$behaviors .= sprintf("\n\t\t'OwnerBehavior' => array(
								'class' => 'OwnerBehavior',
							'ownerColumn' => '%s',
							\t),\n", $column->name);
							break; // once a column is found, we are done

						}
					}


					$behaviors .= "\n);\n";
					return $behaviors;
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
			else if($column->type==='string' && $column->size>0) {
				$length[$column->size][]=$column->name;
				if($column->name == $this->identificationColumn ) {
					$rules[] = "array('{$column->name}', 'unique')";
					$rules[] = "array('{$column->name}', 'identificationColumnValidator')";
				}
			}
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

			function getCreatetimeAttribute($columns) {
				foreach(array('create_time', 'createtime', 'created_at', 'createdat', 'timestamp') as $try)
					foreach($columns as $column)
					if($try == $column->name)
						return sprintf("'%s'", $column->name);

				return 'null';
			}

			function getUpdatetimeAttribute($columns) {
				foreach(array('update_time', 'updatetime', 'changed', 'changed_at') as $try)
					foreach($columns as $column)
					if($try == $column->name)
						return sprintf("'%s'", $column->name);

				return 'null';
			}

	public function guessIdentificationColumn($columns) {
		$found = false;
		foreach($columns as $name => $column) {
			if(!$found 
					&& $column->type != 'datetime'
					&& $column->type==='string' 
					&& !$column->isPrimaryKey) {
				return $column->name;
				$found = true;
			}
		}

		// if the columns contains no column of type 'string', return the
		// first column (usually the primary key)
		if(!$found)
			return reset($columns)->name; 
	}

}

?>
