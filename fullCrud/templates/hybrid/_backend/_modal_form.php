<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->controllerClass; ?> */
/* @var $inputSelector jQuery selector to the select-input of the parent form */
/* @var $pk The primary key field added object */
/* @var $field The field of the newly added object to be used as the key/label of the parent form select-input */

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => $formId."-modal"));

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=><?php echo '$formId'; ?>,
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'type' => '<?php echo $this->formOrientation; ?>',
    ));
?>

<div class="modal-header">
    <button type="button" class="close" data-toggle="modal" data-target="#<?php echo '<?php echo $formId; ?>'; ?>-modal">×</button>
    <?php /* TODO: Change title depending on isNewRecord */ ?>
    <h3><?php echo "<?php echo Yii::t('" . $this->messageCatalog . "', 'Create {model}', array('{model}' => Yii::t('" . $this->messageCatalog . "', '" . $this->class2name($this->modelClass) . "'))); ?>"; ?></h3>
</div>
<div class="modal-body">

<?php echo "<?php\n"; ?>
    $this->renderPartial('/<?php echo $this->controller; ?>/_elements', array(
        'model' => $model,
        'form' => $form,
    ));
    ?>

</div>
<div class="modal-footer">
    <a href="#" class="btn" data-toggle="modal" data-target="#<?php echo '<?php echo $formId; ?>'; ?>-modal">Cancel</a>
    <?php echo "<?php\n"; ?>
    echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('<?php echo $this->controller; ?>/editableCreator', 'render' => true)), array(
        'dataType' => 'json',
        'type' => 'post',
        'success' => 'function(data, config) {
                //$("#loader").show();
                if (data && data.' . $pk . ') {
                    $("#' . $form->id . '").trigger("reset");
                    $("#'.<?php echo '$formId'; ?>.'-modal").modal("hide");
                    $("' . $inputSelector . '")
                        .append($("<option>", { value : data.' . $pk . ', selected: "selected" }).text(data.' . $field . '));
                } else {
                    config.error.call(this, data && data.errors ? data.errors : "Unknown error");
                }
            }',
        'error' => 'function(errors) {
                //$("#loader").show();
                var msg = "";
                if (errors && errors.responseText) {
                    msg = errors.responseText;
                } else {
                    $.each(errors, function(k, v) {
                        msg += v + "<br>";
                    });
                }
                alert(msg);
            }',
        'beforeSend' => 'function() {
                //$("#loader").show();
            }',
        ), array('class' => 'btn btn-primary'));
    ?>

</div>

<?php echo "<?php\n"; ?>
$this->endWidget(); // form
$this->endWidget(); // modal