<div class="wide form">

    <?=
    "<?php \$form=\$this->beginWidget('CActiveForm', array(
           'action'=>Yii::app()->createUrl(\$this->route),
           'method'=>'get',
   )); ?>\n"; ?>

    <?php foreach ($this->tableSchema->columns as $column): ?>
        <?php
        $field = $this->provider()->generateInputField($this->modelClass, $column);
        if (strpos($field, 'password') !== false) {
            continue;
        }
        ?>

        <div class="row">
            <?= "<?php echo \$form->label(\$model,'{$column->name}'); ?>"; ?>
            <?= "\n"; ?>
            <?php if (!$column->isForeignKey): ?>
                <?= "<?php " . $this->provider()->generateActiveField($this->modelClass, $column) . "; ?>"; ?>
            <?php else: ?>
                <?= "<?php " . $this->provider()->generateValueField($this->modelClass, $column, 'search') . "; ?>"; // TODO: itemLabel ?>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>
    <div class="row buttons">
        <?= "<?php echo CHtml::submitButton(Yii::t('" . $this->messageCatalog . "', 'Search')); ?>\n"; ?>
    </div>

    <?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->
