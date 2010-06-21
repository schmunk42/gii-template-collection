<?php
echo "<?php\n";
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	Yii::t('app', 'Update'),
);\n";
?>

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>'View <?php echo $this->modelClass; ?>', 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<?php 
$pk = "\$model->" . $this->tableSchema->primaryKey;
printf('<h1> %s %s #%s </h1>', 
Yii::t('app', 'Update'),
$this->modelClass,
'<?php echo ' . $pk . '; ?>'); ?>

<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>true,
)); \n"; 

echo "echo \$this->renderPartial('_form', array(
	'model'=>\$model,
	'form' =>\$form
	)); ?>\n"; ?>

<div class="row buttons">
	<?php echo "<?php echo CHtml::submitButton(Yii::t('app', 'Update')); ?>\n"; ?>
</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->
