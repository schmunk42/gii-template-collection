<?php

//add editable provider
$this->providers = array('gtc.fullCrud.providers.EditableProvider');

$relations = CActiveRecord::model(Yii::import($this->model))->relations();
if (!empty($relations)) :

echo "<?php
if(!\$ajax){
    Yii::app()->clientScript->registerCss('rel_grid',' 
            .rel-grid-view {padding-top:0px;margin-top: -35px;}
            h3.rel_grid{padding-left: 40px;}
            ');     
}
?>";

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

<?= "<?php
if(!\$ajax || \$ajax == '{$this->class2id($rmodelClassName)}-grid'){
    Yii::beginProfile('{$rmodelRefFiels}.view.grid');
?>"; ?>

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
<h3 class=\"rel_grid\">    
    <?=Yii::t('{$this->messageCatalog}', '{$this->class2name($rmodelClassName)}')?>
    <?php    
        
    \$this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'ajaxButton', 
            'type' => 'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'size' => 'mini',
            'icon' => 'icon-plus',
            'url' => array(
                '/{$controller}/ajaxCreate',
                'field' => '{$rmodelRefFiels}',
                'value' => \$modelMain->primaryKey,
                'ajax' => '{$this->class2id($rmodelClassName)}-grid',
            ),
            'ajaxOptions' => array(
                    'success' => 'function(html) {\$.fn.yiiGridView.update(\'{$this->class2id($rmodelClassName)}-grid\');}'
                    ),
            'htmlOptions' => array(
                'title' => Yii::t('{$this->messageCatalogStandard}', 'Add new record'),
                'data-toggle' => 'tooltip',
            ),                 
        )
    );        
    ?>
";?></h3> 
<?=" 
<?php 

    if (empty(\$modelMain->{$key})) {
        \$model = new {$rmodelClassName};
        \$model->{$rmodelRefFiels} = \$modelMain->primaryKey;
        \$model->save();
        unset(\$model);
    } 
    
    \$model = new {$rmodelClassName}();
    \$model->{$rmodelRefFiels} = \$modelMain->primaryKey;

    // render grid view

    \$this->widget('TbGridView',
        array(
            'id' => '{$this->class2id($rmodelClassName)}-grid',
            'dataProvider' => \$model->search(),
            'template' => '{summary}{items}',
            'summaryText' => '&nbsp;',
            'htmlOptions' => array(
                'class' => 'rel-grid-view'
            ),            
            'columns' => array(
    {$columns}
                array(
                    'class' => 'TbButtonColumn',
                    'buttons' => array(
                        'view' => array('visible' => 'FALSE'),
                        'update' => array('visible' => 'FALSE'),
                        'delete' => array('visible' => 'Yii::app()->user->checkAccess(\"{$this->getRightsPrefix()}.Delete{$key}\")'),
                    ),
                    'deleteButtonUrl' => 'Yii::app()->controller->createUrl(\"{$controller}/delete\", array(\"{$relatedModel->tableSchema->primaryKey}\" => \$data->{$relatedModel->tableSchema->primaryKey}))',
                    'deleteButtonOptions'=>array('data-toggle'=>'tooltip'),                    
                ),
            )
        )
    );
    ?>"
?>


<?= "<?php
    Yii::endProfile('{$rmodelClassName}.view.grid');
}    
?>"; ?>

<?
endforeach;

endif;