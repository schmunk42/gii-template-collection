<?php
//add editable provider
$this->providers = array('gtc.fullCrud.providers.EditableProvider');
?>
<?=
// prepare breadcrumbs & clientscript
"<?php
\$this->setPageTitle(
    Yii::t('{$this->messageCatalog}', '{$this->pluralize($this->class2name($this->modelClass))}')
    . ' - '
    . Yii::t('{$this->messageCatalogStandard}', 'Manage')
);

\$this->breadcrumbs[] = Yii::t('{$this->messageCatalog}', '{$this->pluralize($this->class2name($this->modelClass))}');

?>
"
?>

<?= '<?php $this->widget("TbBreadcrumbs", array("links" => $this->breadcrumbs)) ?>'; ?>

<div class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="btn-group"><?="
        <?php 
        \$this->widget('bootstrap.widgets.TbButton', array(
             'label'=>Yii::t('" . $this->messageCatalogStandard . "','Create'),
             'icon'=>'icon-plus',
             'size'=>'large',
             'type'=>'success',
             'url'=>array('create'),
             'visible'=>(Yii::app()->user->checkAccess('" . $this->getRightsPrefix() . ".*') || Yii::app()->user->checkAccess('" . $this->getRightsPrefix() . ".Create'))
        ));  
        ?>
"?></div>
        <div class="btn-group">
            <h1>
                <i class="<?=$this->icon;?>"></i>
                <?php  echo "<?php echo Yii::t('{$this->messageCatalog}', '{$this->pluralize($this->class2name($this->modelClass))}');?>"?>
            </h1>
        </div>
    </div>
</div>

<?= "<?php Yii::beginProfile('{$this->modelClass}.view.grid'); ?>"; ?>

<?php
// prepare (seven) columns
$count = 0;
$maxColumns = 8; //TODO: you could get this from a provider, keep 8 as default .... OR generator attribute
$columns = "";
$comment = false;
foreach ($this->tableSchema->columns as $column) {

    // skip column with provider function
    if ($this->provider()->skipColumn($this->modelClass, $column)) {
        continue;
    }

    // render, but comment from the 8th column on
    if ($count == $maxColumns) {
        $comment = true;
        $columns .= "            /*\n";
    }
    $column = $this->provider()->generateColumn($this->modelClass, $column);
    $columns .= "            " . $column . ",\n";
    if (substr($column, 0, 1) != '#') {
        $count++;
    } // don't count a commented column
}
if ($comment === true) {
    $columns .= "            */\n";
}
?>


<?=
// render grid view
"<?php
\$this->widget('TbGridView',
    array(
        'id' => '{$this->class2id($this->modelClass)}-grid',
        'dataProvider' => \$model->search(),
        'filter' => \$model,
        #'responsiveTable' => true,
        'template' => '{summary}{pager}{items}{pager}',
        'pager' => array(
            'class' => 'TbPager',
            'displayFirstAndLast' => true,
        ),
        'columns' => array(
            array(
                'class' => 'CLinkColumn',
                'header' => '',
                'labelExpression' => '\$data->{$this->provider()->suggestIdentifier($this->modelClass)}',
                'urlExpression' => 'Yii::app()->controller->createUrl(\"view\", array(\"{$this->tableSchema->primaryKey}\" => \$data[\"{$this->tableSchema->primaryKey}\"]))'
            ),
{$columns}
            array(
                'class' => 'TbButtonColumn',
                'buttons' => array(
                    'view' => array('visible' => 'Yii::app()->user->checkAccess(\"{$this->getRightsPrefix()}.View\")'),
                    'update' => array('visible' => 'FALSE'),
                    'delete' => array('visible' => 'Yii::app()->user->checkAccess(\"{$this->getRightsPrefix()}.Delete\")'),
                ),
                'viewButtonUrl' => 'Yii::app()->controller->createUrl(\"view\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl(\"delete\", array(\"{$this->tableSchema->primaryKey}\" => \$data->{$this->tableSchema->primaryKey}))',
                'viewButtonOptions'=>array('data-toggle'=>'tooltip'),   
                'deleteButtonOptions'=>array('data-toggle'=>'tooltip'),   
            ),
        )
    )
);
?>"
?>

<?= "<?php Yii::endProfile('{$this->modelClass}.view.grid'); ?>"; ?>