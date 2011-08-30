<fieldset>
<legend>magic methods:</legend>
<div style="height:80px;width:99%;overflow:scroll;font-size:85%;">
<?php $model->renderSelection('magicMethods','checkbox',
    array(
        '__construct'=>CHtml::link('__construct',
            'http://de2.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor'),
        '__destruct'=>CHtml::link('__destruct',
            'http://de2.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.destructor'),
        '__call'=>CHtml::link('__call','#'),
        '__callStatic'=>CHtml::link('__callStatic','#'),
        '__get'=>CHtml::link('__get','#'),
        '__set'=>CHtml::link('__set','#'),
        '__isset'=>CHtml::link('__isset','#'),
        '__unset'=>CHtml::link('__unset','#'),
        '__sleep'=>CHtml::link('__sleep','#'),
        '__wakeup'=>CHtml::link('__wakeup','#'),
        '__toString'=>CHtml::link('__toString','#'),
        '__invoke'=>CHtml::link('__invoke','#'),
        '__set_state'=>CHtml::link('__set_state','#'),
        '__clone'=>CHtml::link('__clone','#'),
    ),
    array('style'=>'margin-right:10px;float:left;')
    );
?>
</div>
</fieldset>
<fieldset>
	<div class="row">
		<?php echo $form->labelEx($model,'codeBody'); ?>
		<?php echo $form->textArea($model,'codeBody',array('rows'=>15, 'cols'=>70)); ?>
	</div>
</fieldset>