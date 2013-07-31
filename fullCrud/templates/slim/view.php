<?=
"<?php
\$this->breadcrumbs[Yii::t('{$this->messageCatalog}','{$this->pluralize($this->class2name($this->modelClass))}')] = array('admin');
\$this->breadcrumbs[\$model->{\$model->tableSchema->primaryKey}] = array('view','id'=>\$model->{\$model->tableSchema->primaryKey});
\$this->breadcrumbs[] = Yii::t('{$this->messageCatalog}', 'View');
?>
";?>

<?= '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?=
    "<?php echo Yii::t('" . $this->messageCatalog . "','" . $this->class2name($this->modelClass) . "')?>
    <small><?php echo Yii::t('" . $this->messageCatalog . "','View')?> #<?php echo \$model->" . $this->tableSchema->primaryKey . " ?></small>
    "?>
</h1>



<?= '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>



<div class="row">
    <div class="span8">
        <h2>
            <?= "<?php echo Yii::t('" . $this->messageCatalog . "','Data')?>"; ?>
        </h2>

        <h3>
            <?=
            "<?php echo \$model->" . $this->provider()->suggestIdentifier(
                CActiveRecord::model(Yii::import($this->model))
            ) . "?>"; ?>
        </h3>


        <?=
        "<?php
        \$this->widget(
            'TbDetailView',
            array(
                'data'=>\$model,
                'attributes'=>array(
        ";
        foreach ($this->tableSchema->columns as $column) {
            echo $this->provider()->generateAttribute($column);
        }
        echo "),
            ));
        ?>"?>

    </div>

    <div class="span4">
        <?=
        "<?php \$this->renderPartial('_view-relations',array('model'=>\$model)); ?>";
        ?>
    </div>
</div>