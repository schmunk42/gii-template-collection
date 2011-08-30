<div class="row">
<?php echo $form->labelEx($model,'comment'); ?>
<?php echo $form->textArea($model,'comment',array('rows'=>10, 'cols'=>75)); ?>
<?php $this->widget('ClassGenerator.widgets.XMarkDownReferenceLink', array()); ?>
<?php echo $form->error($model,'comment'); ?>
</div>
