    <div class="row">
        <?php echo $form->labelEx($model,'className'); ?>
        <?php echo $form->textField($model,'className',array('size'=>65)); ?>
        <div class="tooltip">
            LogRoute class name must only contain word characters.
        </div>
        <?php echo $form->error($model,'className'); ?>
    </div>

<div class="row sticky">
	<?php echo $form->labelEx($model,'baseClass'); ?>
	<?php echo $form->textField($model,'baseClass',array('size'=>65)); ?>
	<div class="tooltip">
		This is the class that the new cache class will extend from.
		Please make sure the class exists and can be autoloaded.
	</div>
	<?php echo $form->error($model,'baseClass'); ?>
</div>
