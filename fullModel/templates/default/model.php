<?php
/**
 * This is the template for generating the model class of a specified table.
 * In addition to the default model Code, this adds the CAdvancedArBehavior
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

class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
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
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	public function behaviors()
	{
		return array('CAdvancedArBehavior',
				array('class' => 'ext.CAdvancedArBehavior')
				);
	}

	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => Yii::t('app', '$label'),\n"; ?>
<?php endforeach; ?>
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

<?php
foreach($columns as $name=>$column)
{
	if($column->type==='string')
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n\n";
	}
	else
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n\n";
	}
}
?>
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
