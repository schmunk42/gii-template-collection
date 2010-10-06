<?php
/**
 * This is the template for generating the model class of a specified table.
 * In addition to the default model Code, this adds the CSaveRelationsBehavior
 * to the model class definition.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $modelClass; ?> extends <?php echo 'Base' . $modelClass."\n"; ?>
{
	// Add your model-specific methods here. This file will not be overriden by gtc except you force it.
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function init()
	{
		return parent::init();
	}

	public function __toString() {
		return (string) $this-><?php
			$found = false;
			foreach($columns as $name => $column) {
				if(!$found 
						&& $column->type != 'datetime'
						&& $column->type==='string' 
						&& !$column->isPrimaryKey) {
					echo $column->name;
					$found = true;
				}
			}

			// if the columns contains no column of type 'string', return the
			// first column (usually the primary key)
			if(!$found)
				echo reset($columns)->name; 
			?>;

	}


	public function rules() 
	{
		return array_merge(
			/*array('column1, column2', 'rule'),*/
			parent::rules()
		);
	}

	public function behaviors()
	{
		return array_merge(
			/*array(
				'BehaviourName' => array(
					'class' => 'CWhateverBehavior'
				)
			),*/
			parent::behaviors()
		);
	}
}
