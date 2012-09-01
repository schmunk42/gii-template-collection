<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs['$label'] = array('index');\n";
echo "\$this->breadcrumbs[\$model->{\$model->tableSchema->primaryKey}] = array('view','id'=>\$model->{\$model->tableSchema->primaryKey});\n";
echo "\$this->breadcrumbs[] = Yii::t('app', 'Update');\n";
echo "?>";
?>

<?php
$pk = "\$model->" . $this->tableSchema->primaryKey;
printf('<h1> %s %s #%s </h1>', '<?php echo Yii::t(\'app\', \'Update\');?>', '<?php echo Yii::t(\'app\', \'' . $this->modelClass . '\');?>', '<?php echo ' . $pk . '; ?>');
?>

<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
'model'=>$model));
?>
