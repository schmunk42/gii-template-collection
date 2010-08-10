<div class="row buttons">
<?php echo "<?php\n"; ?>
	echo CHtml::Button(Yii::t('app', 'Cancel'), array('submit' => $returnUrl));  
	if(isset($buttons) && $buttons == 'create')
		echo CHtml::submitButton(Yii::t('app', 'Create')); 
	if(isset($buttons) && $buttons == 'update')
		echo CHtml::submitButton(Yii::t('app', 'Update')); 

<?php echo "?>\n"; ?>
</div>


