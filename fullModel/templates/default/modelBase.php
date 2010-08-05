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

class <?php echo $modelClass . 'Base'; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '<?php echo $tableName; ?>';
	}

	public function rules()
	{
		return array(
<?php
		foreach($rules as $rule) {
			echo "\t\t\t$rule,\n";
		}
?>
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
<?php
		foreach($relations as $name=>$relation) {
			echo "\t\t\t'$name' => $relation,\n";
		}
?>
		);
	}

	public function attributeLabels()
	{
		return array(
<?php
		foreach($labels as $name=>$label) {
			echo "\t\t\t'$name' => Yii::t('app', '$label'),\n";
		}
?>
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

<?php
		foreach($columns as $name=>$column)
		{
			if($column->type==='string' and !$column->isForeignKey)
			{
				echo "\t\t\$criteria->compare('$name', \$this->$name, true);\n";
			}
			else
			{
				echo "\t\t\$criteria->compare('$name', \$this->$name);\n";
			}
		}
		echo "\n";
?>
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
