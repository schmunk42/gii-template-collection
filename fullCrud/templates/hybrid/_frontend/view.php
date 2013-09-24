<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs[Yii::t('".$this->messageCatalog."', '$label')] = array('admin');\n";
echo "\$this->breadcrumbs[] = \$model->{$this->tableSchema->primaryKey};\n";
echo "?>";
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links" => $this->breadcrumbs)) ?>'; ?>

<h1>
    <?=
    "
    <?php echo Yii::t('{$this->messageCatalog}','{$this->class2name($this->modelClass)}'); ?>
    <small>
        <?php echo Yii::t('{$this->messageCatalog}','View')?> #<?php echo \$model->{$this->tableSchema->primaryKey} ?>
    </small>
    ";
    ?>

</h1>

<?php echo '<?php $this->renderPartial("_toolbar", array("model" => $model)); ?>'; ?>

<?php
echo "<b><?php echo CHtml::encode(\$model->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
echo "<?php echo CHtml::link(CHtml::encode(\$model->{$this->tableSchema->primaryKey}), array('view', '{$this->tableSchema->primaryKey}' => \$model->{$this->tableSchema->primaryKey})); ?>\n    <br />\n\n";
$count = 0;
foreach ($this->tableSchema->columns as $column) {
    if ($column->isPrimaryKey)
    continue;
    if (++$count == 7)
    echo "<?php /*\n";
    echo "<b><?php echo CHtml::encode(\$model->getAttributeLabel('{$column->name}')); ?>:</b>\n";
    if ($column->name == 'createtime'
    or $column->name == 'updatetime'
    or $column->name == 'timestamp') {
    echo "    echo Yii::app()->getDateFormatter()->formatDateTime(\$model->{$column->name}, 'medium', 'medium'); ?>\n    <br />\n\n";
    } else {
    echo "<?php echo CHtml::encode(\$model->{$column->name}); ?>\n<br />\n\n";
    }
}
if ($count >= 7)
    echo "    */\n    ?>\n";
?>

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
                'data' => \$model,
                'attributes' => array(
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
        "<?php \$this->renderPartial('_view-relations', array('model' => \$model)); ?>";
        ?>
    </div>
</div>