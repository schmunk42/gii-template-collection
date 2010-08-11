<div class="row buttons">
<?php echo "<?php\n"; ?>
	echo CHtml::Button(Yii::t('app', 'Cancel'), array('onClick' => "$('#{$model}').hide();"));  
	echo CHtml::AjaxSubmitButton(Yii::t('app', 'Create'), array($model.'/create'), array('update' => "#{$model}")); 
<?php echo "?>\n"; ?>
</div>


