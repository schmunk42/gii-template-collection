<div class="row">
<?php echo $form->labelEx($model,'comment'); ?>
<?php $this->widget(
    'BehaviorGenerator.widgets.ddeditor.DDEditor',
    array(
        'model'=>$model,
        'attribute'=>'comment',
        'htmlOptions'=>array('rows'=>10, 'cols'=>75),
        'previewRequest'=>'gii/behavior/preview'));
?>
<?php $this->widget('BehaviorGenerator.widgets.XMarkDownReferenceLink', array()); ?>
<?php echo $form->error($model,'comment'); ?>
</div>
