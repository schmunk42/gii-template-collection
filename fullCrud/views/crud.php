<div class="row">
<?php echo $form->labelEx($model,'model'); ?>
<?php $form->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'model'=>$model,
			'attribute'=>'model',
			'source'=>$this->getModels(),
			'options'=>array(
				'delay'=>100,
				'focus'=>'js:function(event,ui){
				$(this).val($(ui.item).val());
				$(this).trigger(\'change\');
				}',
				),
			'htmlOptions'=>array(
				'size'=>'65',
				),
			));
?>
	<div class="tooltip">
Model class is case-sensitive. It can be either a class name (e.g. <code>Post</code>)
	or the path alias of the class file (e.g. <code>application.models.Post</code>).
	Note that if the former, the class must be auto-loadable.
	</div>
	<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model,'controller'); ?>
	<?php echo $form->textField($model,'controller',array('size'=>65)); ?>
	<div class="tooltip">
	Controller ID is case-sensitive. CRUD controllers are often named after
	the model class name that they are dealing with. Below are some examples:
	<ul>
	<li><code>post</code> generates <code>PostController.php</code></li>
	<li><code>postTag</code> generates <code>PostTagController.php</code></li>
	<li><code>admin/user</code> generates <code>admin/UserController.php</code></li>
	</ul>
	</div>
	<?php echo $form->error($model,'controller'); ?>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model, 'validation'); ?>
	<?php echo $form->dropDownList($model, 'validation', array(
				3 => 'Enable Ajax and Client-side Validation',
				2 => 'Enable Client Validation',
				1 => 'Enable Ajax Validation',
				0 => 'Disable ajax Validation'
				)); ?>
	<div class="tooltip">
	Enables instant Validation of input fields via Yii's form Generator and ajax
	requests after blur() on the field. Since Yii 1.1.7 you can also enable
	client-side validation for most of the validation rules
	</div>
	<?php echo $form->error($model,'validation'); ?>
	</div>

	<div class="row sticky">
	<?php echo $form->labelEx($model,'baseControllerClass'); ?>
	<?php echo $form->textField($model,'baseControllerClass',array('size'=>65)); ?>
	<div class="tooltip">
	This is the class that the new CRUD controller class will extend from.
	Please make sure the class exists and can be autoloaded.
	</div>
	<?php echo $form->error($model,'baseControllerClass'); ?>
	</div>
