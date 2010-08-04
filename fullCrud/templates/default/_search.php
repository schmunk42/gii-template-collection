<div class="wide form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl(\$this->route),
        'method'=>'get',
)); ?>\n"; ?>

<?php foreach($this->tableSchema->columns as $column): ?>
<?php
        $field=$this->generateInputField($this->modelClass,$column);
        if(strpos($field,'password')!==false)
                continue;
?>
        <div class="row">
                <?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
<?php if(!$column->isForeignKey):?>
                <?php echo "<?php ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
<?php else: ?>
                <?php echo "<?php echo ".$this->generateValueField($this->modelClass,$column,'search')."; ?>\n"; ?>
<?php endif; ?>
        </div>
<?php endforeach; ?>
<?php /*foreach($this->getRelations() as $key => $relation)
{
	if($relation[0] == 'CBelongsToRelation'
			or $relation[0] == 'CHasOneRelation'
			or $relation[0] == 'CManyManyRelation')
	{
		printf('<label for="%s">%s</label>', $relation[1], $relation[1]);
		echo "<?php echo CHtml::listData({$relation[1]}::model()->findAll(), 'id', 'nombre'". $this->generateRelation($this->modelClass, $key, $relation)."; ?>\n";
	}
}*/
?>
        <div class="row buttons">
                <?php echo "<?php echo CHtml::submitButton(Yii::t('app', 'Search')); ?>\n"; ?>
        </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->
