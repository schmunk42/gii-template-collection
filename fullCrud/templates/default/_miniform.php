<div class="miniform">
<?php
$ajax = ($this->enable_ajax_validation) ? 'true' : 'false';
echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>$ajax,
)); \n"; 

echo "\tif(isset(\$_POST['returnUrl']))\n";
echo "\t\techo CHtml::hiddenField('returnUrl', \$_POST['returnUrl']);\n";
echo "\techo \$form->errorSummary(\$model);\n";
echo "?>";
?>

<table>
<?php
	foreach($this->tableSchema->columns as $column)
	{
		if($column->isPrimaryKey)
			continue;

		if(!$column->isForeignKey) 
		{

			echo "<tr>\n";
			printf("<td><?php echo %s ?></td>\n", $this->generateActiveLabel($this->modelClass,$column));
			printf("<td><?php %s ?></td>\n", $this->generateActiveField($this->modelClass,$column));
			printf("<td><?php echo %s ?></td>\n", "\$form->error(\$model,'{$column->name}')"); 
			echo "</tr>\n";
		}
	}

?>
</table>	
<?php echo "<?php\n"; ?>
echo CHtml::Button(Yii::t('app', 'Cancel'), array(
			'onClick' => "$('#dialog_<?php echo strtolower($this->modelClass);?>').dialog('close');"));  
echo CHtml::AjaxSubmitButton(Yii::t('app', 'Create'), array(
			'<?php echo strtolower($this->modelClass); ?>/miniCreate'), array(
				'update' => "#dialog_<?php echo strtolower($this->modelClass); ?>"), array(
				'id' => 'submit_<?php echo strtolower($this->modelClass); ?>')); 
$this->endWidget(); 
<?php echo "\n?>"; ?>
</div> <!-- form -->
