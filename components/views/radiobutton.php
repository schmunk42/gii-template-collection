<?php
echo CHtml::openTag('div', array('id' => $id, 'class' => 'relation'));
echo CHtml::ActiveRadiobuttonList(
		$model, 
		$field, 
		$data,
		$htmlOptions);
echo CHtml::closeTag('div');

