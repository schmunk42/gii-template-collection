<div class="row">
<?php echo $form->labelEx($model,'comment'); ?>
<?php $this->widget(
    'LogRouteGenerator.widgets.ddeditor.DDEditor',
    array(
        'model'=>$model,
        'attribute'=>'comment',
        'htmlOptions'=>array('rows'=>10, 'cols'=>75),
        'previewRequest'=>'gii/logroute/preview'));
?>
<?php $this->widget('LogRouteGenerator.widgets.XMarkDownReferenceLink', array()); ?>
<?php echo $form->error($model,'comment'); ?>
</div>
