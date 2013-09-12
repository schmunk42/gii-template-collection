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
    <div class="span7">
        <h2>
            <?= "<?php echo Yii::t('" . $this->messageCatalog . "','Data')?>"; ?>
            <small>
                <?=
                "<?php echo \$model->" . $this->provider()->suggestIdentifier(
                    CActiveRecord::model(Yii::import($this->model))
                ) . "?>"; ?>
            </small>
        </h2>

        <?=
        "<?php
        \$this->widget(
            'TbDetailView',
            array(
                'data'=>\$model,
                'attributes'=>array(
        ";
        ?>
        <?php
        foreach ($this->tableSchema->columns as $column) {
            echo $this->provider()->generateAttribute($this->model, $column);
        }
        ?>
        <?=
        "   ),
        )); ?>"?>

    </div>

    <div class="span5">
        <?=
        "<?php \$this->renderPartial('_view-relations',array('model'=>\$model)); ?>";
        ?>
    </div>
</div>