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
    if ($count == 8) {
        $columns .= "        /*\n";
    }
    $column = $this->provider()->generateColumn($this->modelClass, $column);
    $columns .= "        " . $column . ",\n";
    if (substr($column, 0, 1) != '#') {
        $count++;
    } // don't count a commented column
}
if ($count >= 9) {
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
        'template'=>'{pager}{summary}{items}{pager}',
        'pager' => array(
            'class' => 'TbPager',
            'displayFirstAndLast' => true,
        ),
        'columns'=> array(
            array(
                'class'=>'CLinkColumn',
                'header'=>'',
                'labelExpression'=> '\$data->{$this->provider()->suggestIdentifier($this->modelClass)}',
                'urlExpression'=> 'Yii::app()->controller->createUrl(\"view\", array(\"{$this->tableSchema->primaryKey}\" => \$data[\"{$this->tableSchema->primaryKey}\"]))'
            ),
            {$columns}
            array(
                'class'=>'TbButtonColumn',
                'buttons' => array(
                    'view' => array('visible' => 'Yii::app()->user->checkAccess(\"{$this->getRightsPrefix()}.View\")'),
                    'update' => array('visible' => 'Yii::app()->user->checkAccess(\"{$this->getRightsPrefix()}.Update\")'),
                    'delete' => array('visible' => 'Yii::app()->user->checkAccess(\"{$this->getRightsPrefix()}.Delete\")'),
                ),
                'viewButtonUrl'   => 'Yii::app()->controller->createUrl(\"view\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
                'updateButtonUrl' => 'Yii::app()->controller->createUrl(\"update\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl(\"delete\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
            ),
        )
    )
);
?>"
?>
