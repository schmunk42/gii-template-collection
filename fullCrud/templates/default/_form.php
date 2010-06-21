<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
?>
	<div class="row">
		<?php 
		if(!$column->isForeignKey) 
		{
			echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; 
			echo "<?php ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; 
			echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; 
		}
?>
	</div>

<?php
}
?>

<?php
foreach($this->getRelations() as $key => $relation)
{
	if($relation[0] == 'CBelongsToRelation' 
			or $relation[0] == 'CHasOneRelation' 
			or $relation[0] == 'CManyManyRelation')
	{
		printf('<label for="%s">Belonging %s</label>', $relation[1], $relation[1]);
		echo "<?php ". $this->generateRelation($this->modelClass, $key, $relation)."; ?>\n"; ?>
			<?php
	}
}
?>
