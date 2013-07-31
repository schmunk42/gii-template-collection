<p> The <?php echo ucfirst($this->modelclass); ?> <?php echo "<?php echo \$model; ?>"; ?> has been successfully
    created </p>

<?php
echo "<?php echo CHtml::Button(Yii::t('app', 'Back'), array('id' => \$relation.'_done')); ?>";
echo "<?php echo CHtml::Button(Yii::t('app', 'Add another " . $this->modelclass . "'), array('id' => \$relation.'_create'));";
?>

