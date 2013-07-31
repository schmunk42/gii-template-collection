<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs[Yii::t('" . $this->messageCatalog . "','$label')] = array('admin');\n";
echo "\$this->breadcrumbs[] = \$model->{$this->tableSchema->primaryKey};\n";
echo "?>";
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?php
    echo "<?php echo Yii::t('" . $this->messageCatalog . "','" . $this->class2name($this->modelClass) . "')?>";
    echo " <small><?php echo Yii::t('" . $this->messageCatalog . "','View')?> #<?php echo \$model->" . $this->tableSchema->primaryKey . " ?></small>";
    ?>
</h1>



<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>



<div class="row">
    <div class="span8">
        <h2>
            <?php echo "<?php echo Yii::t('" . $this->messageCatalog . "','Data')?>"; ?>
        </h2>

        <h3>
            <?php echo "<?php echo \$model->" . $this->provider()->suggestIdentifier(
                    CActiveRecord::model(Yii::import($this->model))
                ) . "?>"; ?>
        </h3>


        <?php
        echo "<?php
    \$this->widget('TbDetailView', array(
    'data'=>\$model,
    'attributes'=>array(
    ";
        foreach ($this->tableSchema->columns as $column) {
            echo $this->provider()->generateAttribute($column);
        }
        echo "),
        )); ?>";
        ?>

    </div>

    <div class="span4">
        <?php echo "
        <?php \$this->renderPartial('_view-relations',array('model'=>\$model)); ?>
        ";
        ?>
    </div>
</div>