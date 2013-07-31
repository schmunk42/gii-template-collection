<?php
echo CHtml::openTag('div', array('id' => $id, 'class' => 'relation'));
echo CHtml::ActiveListBox(
    $model,
    $field,
    $data,
    $htmlOptions
);

echo CHtml::closeTag('div');
?>
