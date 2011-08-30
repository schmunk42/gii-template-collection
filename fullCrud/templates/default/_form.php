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

echo "\techo \$form->errorSummary(\$model);\n";
echo "?>";
?>

	<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;

	if(!$column->isForeignKey
			&& $column->name != 'createtime'
			&& $column->name != 'updatetime'
			&& $column->name != 'timestamp') {
		echo "<div class=\"row\">\n";
		echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; 
		echo "<?php ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; 
		echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n";
		$placholder = "_HINT_".$this->modelClass.".".$column->name."";
		echo "<?php if('".$placholder."' != \$hint = Yii::t('app', '".$placholder."')) echo \$hint; ?>\n";
		echo "</div>\n\n";
	}
}

foreach($this->getRelations() as $key => $relation)
{
	if($relation[0] == 'CBelongsToRelation' 
			|| $relation[0] == 'CHasOneRelation' 
			|| $relation[0] == 'CManyManyRelation')
	{
		echo "<div class=\"row\">\n";
		/*printf("<label for=\"%s\"><?php echo Yii::t('app', 'Belonging').' '.Yii::t('app', '%s'); ?></label>\n", $relation[1], $relation[1]);
		 */
		printf("<label for=\"%s\"><?php echo Yii::t('app', '%s'); ?></label>\n", $key, ucfirst($key));
		echo "<?php ". $this->generateRelation($this->modelClass, $key, $relation)."; ?><br />\n";
		echo "</div>\n\n";
	}
}
?>

<?php echo "<?php
echo CHtml::Button(Yii::t('app', 'Cancel'), array(
			'submit' => array('". strtolower($this->modelClass) ."/admin'))); 
echo CHtml::submitButton(Yii::t('app', 'Save')); 
\$this->endWidget(); ?>\n";  ?>
</div> <!-- form -->
