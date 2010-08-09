<div class="form">
<p class="note">
	<?php echo "<?php echo Yii::t('app','Fields with');?> <span class=\"required\">*</span> <?php echo Yii::t('app','are required');?>";?>.
</p>

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
	
	foreach($this->getRelations() as $key => $relation)
	{
		if($relation[0] == 'CBelongsToRelation' 
				|| $relation[0] == 'CHasOneRelation' 
				|| $relation[0] == 'CManyManyRelation')
		{
			printf("<label for=\"%s\"><?php echo Yii::t('app', 'Belonging').' '.Yii::t('app', '%s'); ?></label>\n", $relation[1], $relation[1]);
			echo "<?php ". $this->generateRelation($this->modelClass, $key, $relation)."; ?>\n";
		}
	}
?>

	<?php echo "<?php
if(isset(\$buttons))
\t\$this->renderPartial('buttons', array('model' => \$model)); 
\$this->endWidget(); ?>\n";  ?>
	</div> <!-- form -->
