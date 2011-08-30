<div class="form">
<?php
$ajax = ($this->enable_ajax_validation) ? 'true' : 'false';
echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>$ajax,
)); \n"; 

echo "\techo \$form->errorSummary(\$model);\n";
echo "?>";
?>

<?php
	foreach($this->tableSchema->columns as $column)
	{
		if($column->isPrimaryKey)
			continue;

		if(!$column->isForeignKey) 
		{
			echo "<div class=\"row\">\n";
			echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; 
			echo "<?php ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; 
			echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; 
			echo "</div>\n\n";
		}
	}

?>

<?php echo "<?php\n"; ?>
echo CHtml::Button(
	Yii::t('app', 'Cancel'),
	array(
		'onClick' => "$('#".$relation."_dialog').dialog('close');"
	));
echo CHtml::Button(
	Yii::t('app', 'Create'),
	array(
		'id' => "submit_".$relation
	));
$this->endWidget(); 
<?php echo "\n?>"; ?>
</div> <!-- form -->
