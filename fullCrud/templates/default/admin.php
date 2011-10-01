<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "if(!isset(\$this->breadcrumbs))\n
\$this->breadcrumbs=array(
	'$label'=>array(Yii::t('app', 'index')),
	Yii::t('app', 'Manage'),
);\n";
?>

if(!isset($this->menu))
$this->menu=array(
		array('label'=>Yii::t('app', 'List') . ' <?php echo $this->modelClass; ?>',
			'url'=>array('index')),
		array('label'=>Yii::t('app', 'Create') . ' <?php echo $this->modelClass; ?>',
		'url'=>array('create')),
	);

<?php echo '?>'; ?>

<h2> <?php 
echo "<?php echo Yii::t('app', 'Manage'); ?> ";
echo $this->pluralize($this->class2name($this->modelClass)); ?></h2>

<?php echo "<?php\n"; ?>
 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
	echo "\t\t".$this->codeProvider->generateValueField($this->modelClass, $column).",\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
