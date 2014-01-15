<div class="">
    <p class="alert">
        <?= "<?php echo Yii::t('" . $this->messageCatalog . "', 'Fields with <span class=\"required\">*</span> are required.'); ?>\n"; ?>
    </p><?=
    "
    <?php

    \$form = \$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => '{$this->class2id($this->modelClass)}-form',
        'enableAjaxValidation' => " . ($this->validation == 1 || $this->validation == 3 ? 'true' : 'false') . ",
        'enableClientValidation' => " . ($this->validation == 1 || $this->validation == 3 ? 'true' : 'false') . ",
        'type' => '{$this->formOrientation}',
    ));

    echo \$form->errorSummary(\$model);

    if (!isset(\$elementsViewAlias)) {
        \$elementsViewAlias = '_elements';
    }

    \$this->renderPartial(\$elementsViewAlias, array(
        'model' => \$model,
        'form' => \$form,
    ));
    ?>";
    ?>

    <div class="form-actions"><?=
        "
        <?php
        echo CHtml::Button(Yii::t('{$this->messageCatalog}', 'Cancel'), array(
                'submit' => (isset(\$_GET['returnUrl'])) ? \$_GET['returnUrl'] : array('" . $this->controllerID . "/admin'),
                'class' => 'btn'
            )
        );
        echo ' ';
        echo CHtml::submitButton(Yii::t('{$this->messageCatalog}', 'Save'), array(
                'class' => 'btn btn-primary'
            )
        );
        ?>";
        ?>
    </div>

    <?php echo "<?php \$this->endWidget() ?>"; ?>

</div> <!-- form -->

<!-- Modal create-forms referenced to from create buttons (if any) -->
<?=
"
<?php
foreach (array_reverse(\$this->clips->toArray(), true) as \$key => \$clip) { // Reverse order for recursive modals to render properly
    if (strpos(\$key, \"modal:\") === 0) {
        echo \$clip;
    }
}
?>";
?>

