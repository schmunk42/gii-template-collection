<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array(Yii::t('app', 'index')),
	Yii::t('app', 'Create'),
);\n";
?>

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<?php printf('<h1> %s %s </h1>',
		Yii::t('app', 'Create'),
		$this->modelClass); ?>

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
	<?php echo "<?php echo CHtml::submitButton(Yii::t('app', 'Create')); ?>\n"; ?>
</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div>
