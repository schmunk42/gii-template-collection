<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn},
);\n";
?>

$this->menu=array(
	array('label'=>Yii::t('app', 'List') . ' <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>Yii::t('app', 'Create') . ' <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>Yii::t('app', 'Update') . ' <?php echo $this->modelClass; ?>', 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>Yii::t('app', 'Delete') . ' <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('app', 'Manage') . ' <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<h1>View <?php echo $this->modelClass." #<?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column) 
{
	if($column->isForeignKey) {
		foreach($this->relations as $key => $relation) {
			if($relation[2] == $column->name) {
				$columns = CActiveRecord::model($relation[1])->tableSchema->columns;
				$suggestedfield = $this->suggestName($columns);
				echo "\t\t'{$key}.{$suggestedfield->name}',\n";
			}
		}
	}
	else
		echo "\t\t'".$column->name."',\n";
	}
?>
	),
)); ?>


<?php
foreach(CActiveRecord::model($this->model)->relations() as $key => $relation)	
{
	if($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation') 
	{
		$model = CActiveRecord::model($relation[1]);

		$suggestedtitle = $this->suggestName($model->tableSchema->columns);

		printf("<br /><h2> This %s belongs to this %s: </h2>\n", $relation[1], $this->modelClass);
		echo CHtml::openTag('ul');
		printf("<?php foreach(\$model->%s as \$foreignobj) { \n
				printf('<li>%%s</li>', CHtml::link(\$foreignobj->%s, array('%s/view', 'id' => \$foreignobj->".$model->tableSchema->primaryKey.")));\n
				} ?>", $key, $suggestedtitle->name, strtolower($relation[1])); 
		echo CHtml::closeTag('ul');
	}
}
?>
