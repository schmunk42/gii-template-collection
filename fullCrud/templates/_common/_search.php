<div class="wide form">

    <?=
    "<?php
    \$form = \$this->beginWidget('TbActiveForm', array(
        'action' => Yii::app()->createUrl(\$this->route),
        'method' => 'get',
    )); ?>";
    ?>

<?php foreach ($this->tableSchema->columns as $column):?>

    <?php
    // skip column with provider function
    if ($this->provider()->skipColumn($this->modelClass, $column)) {
        continue;
    }
    ?>

    <div class="row">
        <?= "<?php echo \$form->label(\$model, '{$column->name}'); ?>\n"; ?>
        <?= "<?php " . $this->provider()->generateActiveField($this->modelClass, $column, 'search') . "; ?>\n"; ?>
    </div>

<?php endforeach; ?>

    <div class="row buttons">
        <?= "<?php echo CHtml::submitButton(Yii::t('" . $this->messageCatalogStandard . "', 'Search')); ?>\n"; ?>
    </div>

    <?= "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->
