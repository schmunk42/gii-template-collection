<?php
$relations = CActiveRecord::model(Yii::import($this->model))->relations();
if (!empty($relations)) :
    ?>

<!--
<h2>
    <?= "<?php echo Yii::t('{$this->messageCatalogStandard}', 'Relations') ?>"; ?>
</h2>
-->

<?php
foreach ($relations as $key => $relation):

    $controller     = $this->resolveController($relation);
    $relatedModel   = CActiveRecord::model($relation[1]);
    $pk             = $relatedModel->tableSchema->primaryKey;
    $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);
    $scopes         = $relatedModel->scopes();
    $scope          = (isset($scopes['crud'])) ? 'crud' : '';
    $rmodelClassName      =  $relation[1];
    $rmodelRefFiels      =  $relation[2];

    // TODO: currently composite PKs are omitted
    if (is_array($pk)) {
        continue;
    }
    // BELONGS_TO relations are rendered in detail view
    if ($relation[0] == 'CBelongsToRelation') {
        continue;
    } elseif ($relation[0] == 'CHasOneRelation') {
        //UN $recordsWrapper = "\$records = array(\$model->{$key}(array('limit' => 250, 'scopes' => '{$scope}')));";
        continue; //+UN
    } else {
        $recordsWrapper = "\$records = \$model->{$key}(array('limit' => 250, 'scopes' => '{$scope}'));"; // TODO: move to ajax list
        

    }
    
    ?>

<h2><?="
    <?php echo Yii::t('{$this->messageCatalog}', '{$this->pluralize($rmodelRefFiels)}'); ?>
";?></h2> 

<?= "<?php Yii::beginProfile('{$rmodelRefFiels}.view.grid'); ?>"; ?>

<?php
// prepare (seven) columns
$count = 0;
$maxColumns = 8;
$columns = "";
//$rmodel = new $rmodelClassName;
foreach ($relatedModel->tableSchema->columns as $column) {
    
    if($relatedModel->tableSchema->primaryKey == $column->name){
        continue;
    }
    if($rmodelRefFiels == $column->name){
        continue;
    }
    
    // render, but comment from the 8th column on
    if ($count == $maxColumns) {
        $columns .= "            /*\n";
    }
    $column = $this->provider()->generateColumn($rmodelClassName, $column,$controller);
    $columns .= "            " . $column . ",\n";
    if (substr($column, 0, 1) != '#') {
        $count++;
    } // don't count a commented column
}
if ($count >= $maxColumns+1) {
    $columns .= "            */\n";
}

?>
<?=" 
<?php 
\$model = new {$rmodelClassName}();
\$model->{$rmodelRefFiels} = \$modelMain->primaryKey;

// render grid view

\$this->widget('\TbGridView',
    array(
        'id' => '{$this->class2id($rmodelClassName)}-grid',
        'dataProvider' => \$model->search(),
        #'responsiveTable' => true,
        'template' => '{items}',
        'pager' => array(
            'class' => '\TbPager',
            'displayFirstAndLast' => true,
        ),
        'columns' => array(
{$columns}
            array(
                'class' => '\TbButtonColumn',
                'buttons' => array(
                    'view' => array('visible' => 'FALSE'),
                    'update' => array('visible' => 'FALSE'),
                    'delete' => array('visible' => 'Yii::app()->user->checkAccess(\"{$this->getRightsPrefix()}.Delete{$key}\")'),
                ),
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl(\"delete\", array(\"{$relatedModel->tableSchema->primaryKey}\" => \$data->{$relatedModel->tableSchema->primaryKey}))',
            ),
        )
    )
);
?>"
?>


<?= "<?php Yii::endProfile('{$rmodelClassName}.view.grid'); ?>"; ?>

<?
endforeach;

endif;