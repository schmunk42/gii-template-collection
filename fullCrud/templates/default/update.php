<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "if(!isset(\$this->breadcrumbs))\n
\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$this->identificationColumn}=>array('view','{$this->identificationColumn}'=>\$model->{$this->identificationColumn}),
	Yii::t('app', 'Update'),
);\n";
?>

if(!isset($this->menu))
$this->menu=array(
	array('label'=>Yii::t('app', 'List') . ' <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>Yii::t('app', 'Create') . ' <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>Yii::t('app', 'View') . ' <?php echo $this->modelClass; ?>', 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>Yii::t('app', 'Manage') . ' <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<?php 
$pk = "\$model->" . $this->identificationColumn;
printf('<h1> %s %s #%s </h1>',
'<?php echo Yii::t(\'app\', \'Update\');?>',
$this->modelClass,
'<?php echo ' . $pk . '; ?>'); ?>

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
			'model'=>$model));
?>
