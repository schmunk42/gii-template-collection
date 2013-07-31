<div class="view">

    <?php
    echo "    <b><?php echo CHtml::encode(\$data->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
    echo "    <?php echo CHtml::link(CHtml::encode(\$data->{$this->tableSchema->primaryKey}), array('view', '{$this->tableSchema->primaryKey}'=>\$data->{$this->tableSchema->primaryKey})); ?>\n    <br />\n\n";
    $count = 0;
    foreach ($this->tableSchema->columns as $column) {
        if ($column->isPrimaryKey) {
            continue;
        }
        if (++$count == 7) {
            echo "    <?php /*\n";
        }
        echo "    <b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
        if ($column->name == 'createtime'
            or $column->name == 'updatetime'
            or $column->name == 'timestamp'
        ) {
            echo "    echo Yii::app()->getDateFormatter()->formatDateTime(\$data->{$column->name}, 'medium', 'medium'); ?>\n    <br />\n\n";
        } else {
            echo "    <?php echo CHtml::encode(\$data->{$column->name}); ?>\n    <br />\n\n";
        }
    }
    if ($count >= 7) {
        echo "    */ ?>\n";
    }
    ?>

</div>
