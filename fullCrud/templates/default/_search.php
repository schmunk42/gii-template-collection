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
        <div class="row buttons">
                <?php echo "<?php echo CHtml::submitButton(Yii::t('app', 'Search')); ?>\n"; ?>
        </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->
