<?php

class RelationProvider extends GtcCodeProvider
{

    public function generateRelationHeader($relationName, $relationInfo, $controller)
    {
        $code = "";
        $code .= "
    \$this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array(
                'label'=>'" . ucfirst($relationName) . "',
                'icon'=>'icon-list-alt',
                'url'=> array('/{$controller}/admin')
            ),
            array(
                'icon'=>'icon-plus',
                'url'=>array(
                    '/{$controller}/create',
                    '{$relationInfo[1]}' => array('{$relationInfo[2]}'=>\$model->{\$model->tableSchema->primaryKey})
                    )
                ),
            ),
        )
    );";

        return $code;
    }

    public function generateRelationField(
        $model,
        $relationName,
        $relationInfo,
        $captureOutput = false
    ) {

        if ($relationInfo[0] == 'CBelongsToRelation'
            || $relationInfo[0] == 'CHasOneRelation'
            || $relationInfo[0] == 'CManyManyRelation'
        ) {

            $relatedModel = CActiveRecord::model($relationInfo[1]);
            if ($columns = $relatedModel->tableSchema->columns) {

                $suggestedfield = $this->codeModel->provider()->suggestIdentifier($relatedModel);
                $field          = current($columns);
                $style          = $relationInfo[0] == 'CManyManyRelation' ? 'multiselect' : 'dropdownlist';

                if (is_object($field)) {
                    if ($relationInfo[0] == 'CHasOneRelation') {
                        return "if (\$model->{$relationName} !== null) echo \$model->{$relationName}->{$suggestedfield};";
                    }

                    // we always allow empty, so the does not accidentally select the first value
                    $allowEmpty = true;

                    return ("\$this->widget(
                        'Relation',
                        array(
                            'model' => \$model,
                            'relation' => '{$relationName}',
                            'fields' => '{$suggestedfield}',
                            'allowEmpty' => " . ($allowEmpty ? "true" : "false") . ",
                            'style' => '{$style}',
                            'htmlOptions' => array(
                                'checkAll' => 'all'),
                            )
                        " . ($captureOutput ? ", true" : "") . ")");
                }
            }
        }
    }

}