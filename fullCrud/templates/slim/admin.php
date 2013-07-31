<?=
// prepare breadcrumbs & clientscript
"
<?php
\$this->breadcrumbs[] = Yii::t('{$this->messageCatalog}','{$this->pluralize($this->class2name($this->modelClass))}');
Yii::app()->clientScript->registerScript('search', \"
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update(
            '{$this->class2id($this->modelClass)}-grid',
            {data: $(this).serialize()}
        );
        return false;
    });
    \");
?>
"
?>

<?= '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?=
    // headline
    "
    <?php echo Yii::t('{$this->messageCatalog}', '{$this->pluralize($this->class2name($this->modelClass))}'); ?>
    <small><?php echo Yii::t('{$this->messageCatalog}', 'Manage'); ?></small>
    ";
    ?>

</h1>

<?= '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>


<?php
// prepare (seven) columns
$count = 0;
$columns = "";
foreach ($this->tableSchema->columns as $column) {
    // render, but comment from the 8th column on
    if ($count == 7) {
        $columns .= "        /*\n";
    }
    // omit text fields
    if (strtoupper($column->dbType) == 'TEXT') {
        $columns .= "#";
    }
    else {
        $count++;
    }
    $columns .= $this->provider()->generateColumn($this->modelClass, $column) . ",\n";
}

if ($count >= 8) {
    $columns .= "        */\n";
}
?>


<?=
// render grid view
"<?php
\$this->widget('TbGridView',
    array(
        'id'=>'{$this->class2id($this->modelClass)}-grid',
        'dataProvider'=>\$model->search(),
        'filter'=>\$model,
        'pager' => array(
            'class' => 'TbPager',
            'displayFirstAndLast' => true,
        ),
        'columns'=> array(
            array('header'=>'','value'=>'\$data[\"{$this->provider()->suggestIdentifier($this->modelClass)}\"]'),
            {$columns}
            array(
                'class'=>'TbButtonColumn',
                'viewButtonUrl'   => 'Yii::app()->controller->createUrl(\"view\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
                'updateButtonUrl' => 'Yii::app()->controller->createUrl(\"update\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl(\"delete\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
            ),
        )
    )
);
?>"
?>
