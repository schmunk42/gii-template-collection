<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs['$label'] = array('admin');\n";
echo "\$this->breadcrumbs[] = Yii::t('app', 'Create');\n";
echo "?>";
?>

<?php printf('<h1> %s %s </h1>', Yii::t('app', 'Create'), $this->modelClass); ?>

<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
'model' => $model,
'buttons' => 'create'));

?>

