<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs[] = Yii::t('" . $this->messageCatalog . "','" . $label . "');\n";
?>


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?php
    echo "<?php echo Yii::t('" . $this->messageCatalog . "', '" . $this->pluralize($this->class2name($this->modelClass)) . "'); ?> ";
    echo "<small><?php echo Yii::t('" . $this->messageCatalog . "', 'Manage'); ?></small>";
    ?>

</h1>

<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<?php echo '<?php ' ?>
$this->widget('TbGridView',
    array(
        'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'pager' => array(
        'class' => 'TbPager',
        'displayFirstAndLast' => true,
    ),
    'columns'=>array(
<?php
$count = 0;
foreach ($this->tableSchema->columns as $column) {
    if ($count == 7) {
        echo "        /*\n";
    }

    if (strtoupper($column->dbType) == 'TEXT') {
        #echo "#";
        continue;
    }
    else {
        $count++;
    }
    echo "        " . $this->provider()->generateColumn($this->modelClass, $column) . ",\n"; // TODO  "itemLabel"
}

if ($count >= 8) {
    echo "        */\n";
}
?>
        array(
            'class'=>'TbButtonColumn',
            'viewButtonUrl' => "Yii::app()->controller->createUrl('view', array('<?php echo $this->tableSchema->primaryKey; ?>' => \$data-><?php echo $this->tableSchema->primaryKey; ?>))",
            'updateButtonUrl' => "Yii::app()->controller->createUrl('update', array('<?php echo $this->tableSchema->primaryKey; ?>' => \$data-><?php echo $this->tableSchema->primaryKey; ?>))",
            'deleteButtonUrl' => "Yii::app()->controller->createUrl('delete', array('<?php echo $this->tableSchema->primaryKey; ?>' => \$data-><?php echo $this->tableSchema->primaryKey; ?>))",
        ),
    )
)); ?>
