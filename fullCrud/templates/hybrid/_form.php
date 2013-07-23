<div class="">
    <p class="alert">
        <?php echo "<?php echo Yii::t('" . $this->messageCatalog . "','Fields with <span class=\"required\">*</span> are required.');?> \n"; ?>
    </p>


    <?php
    // EChosen selects does not currently update when new options are added
    if (false) echo "<?php
    \$this->widget('echosen.EChosen',
        array('target'=>'select')
    );
    ?>"; ?>

    <?php echo "<?php\n"; ?>

    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'<?php echo $this->class2id($this->modelClass); ?>-form',
    'enableAjaxValidation'=><?php echo $this->validation == 1 || $this->validation == 3 ? 'true' : 'false'; ?>,
    'enableClientValidation'=><?php echo $this->validation == 2 || $this->validation == 3 ? 'true' : 'false'; ?>,
    'type' => '<?php echo $this->formOrientation; ?>',
    ));

    echo $form->errorSummary($model);

    $this->renderPartial('_elements', array(
        'model' => $model,
        'form' => $form,
    ));
    <?php echo '?>'; ?>

    <div class="form-actions">
        <?php
        echo "
    <?php
        echo CHtml::Button(Yii::t('" . $this->messageCatalog . "', 'Cancel'), array(
            'submit' => (isset(\$_GET['returnUrl']))?\$_GET['returnUrl']:array('" . strtolower($this->modelClass) . "/admin'),
            'class' => 'btn'
            ));
        echo ' '.CHtml::submitButton(Yii::t('" . $this->messageCatalog . "', 'Save'), array(
            'class' => 'btn btn-primary'
            ));
    ?>\n";
        ?>
</div>

<?php echo "<?php \$this->endWidget() ?>"; ?>

</div> <!-- form -->

<!-- Modal create-forms referenced to from create buttons (if any) -->
<?php echo "<?php\n"; ?>
foreach (array_reverse($this->clips->toArray(), true) as $key=>$clip) { // Reverse order for recursive modals to render properly
    if (strpos($key, "modal:") === 0) {
        echo $clip;
    }
}
?>
