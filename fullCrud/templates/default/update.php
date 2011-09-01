<?php
echo "<?php\n";
$nameColumn = GHelper::guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "if(!isset(\$this->breadcrumbs))\n
\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	Yii::t('app', 'Update'),
);\n";
?>

if(!isset($this->menu))
$this->menu=array(
	array('label'=>Yii::t('app', 'List') . ' ' . Yii::t('app','<?php echo $this->modelClass; ?>'), 'url'=>array('index')),
	array('label'=>Yii::t('app', 'Create') . ' ' . Yii::t('app','<?php echo $this->modelClass; ?>'), 'url'=>array('create')),
	array('label'=>Yii::t('app', 'View') . ' ' . Yii::t('app','<?php echo $this->modelClass; ?>'), 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>Yii::t('app', 'Manage') . ' ' . Yii::t('app','<?php echo $this->modelClass; ?>'), 'url'=>array('admin')),
);
?>

<?php 
$pk = "\$model->" . $this->tableSchema->primaryKey;
printf('<h1> %s %s #%s </h1>',
'<?php echo Yii::t(\'app\', \'Update\');?>',
'<?php echo Yii::t(\'app\', \''.$this->modelClass.'\');?>',
'<?php echo ' . $pk . '; ?>'); ?>

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
			'model'=>$model));
?>
