<div class="row buttons">
<?php echo "<?php\n"; ?>
echo CHtml::Button(Yii::t('app', 'Cancel'), array(
			'onClick' => "$('#{$model}').hide();"));  
echo CHtml::hiddenField('returnUrl', 'close');
echo CHtml::AjaxSubmitButton(Yii::t('app', 'Create'), array(
			$model.'/create'), array(
				'update' => "#{$model}"), array(
					'id' => 'submit_'.$model)); 
<?php echo "?>\n"; ?>
</div>
