	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Lookup::items('PostStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

At model:

public function getGenderOptions(){
    return array('M' => 'Male', 'F' => 'Female');
}
At view:

<?php echo CHtml::dropDownList('listname', $select,
              $model->genderOptions,
              array('empty' => '(Select a gender'));

              