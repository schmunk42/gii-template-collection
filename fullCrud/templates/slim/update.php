<?php
$label = $this->pluralize($this->class2name($this->modelClass));
echo "<?php\n";
echo "\$this->breadcrumbs[Yii::t('".$this->messageCatalog."','$label')] = array('admin');\n";
echo "\$this->breadcrumbs[\$model->{\$model->tableSchema->primaryKey}] = array('view','id'=>\$model->{\$model->tableSchema->primaryKey});\n";
echo "\$this->breadcrumbs[] = Yii::t('".$this->messageCatalog."', 'Update');\n";
echo "?>";
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?php
    echo "<?php echo Yii::t('".$this->messageCatalog."','".$this->class2name($this->modelClass)."')?>";
    echo " <small><?php echo Yii::t('".$this->messageCatalog."','Update')?> #<?php echo \$model->" . $this->tableSchema->primaryKey . " ?></small>";
    ?>
</h1>

<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
'model'=>$model));
?>
