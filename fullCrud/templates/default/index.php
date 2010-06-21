<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs = array(
	'$label',
	Yii::t('app', 'Index'),
);\n";
?>

$this->menu=array(
	array('label'=>Yii::t('app', 'Create') . ' <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>Yii::t('app', 'Manage') . ' <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<h1><?php echo $label; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
