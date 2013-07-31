<?php

class GtcRelationProvider extends GtcCodeProvider
{

    public function generateActiveLabel($model, $column)
    {
        foreach ($this->codeModel->getRelations() as $key => $relation) {
            if ($relation[2] == $column->name) {
                if ($relation[0] == "CBelongsToRelation") {
                    return null; // continue with providers
                } else {
                    // omit relations, they are rendered by GtcRelationProvider
                    return false; // no output, don't continue
                }

            }
        }
    }

    public function generateActiveField($model, $column)
    {
        foreach ($this->codeModel->getRelations() as $key => $relation) {
            if ($relation[2] == $column->name) {
                if ($relation[0] == "CBelongsToRelation") {
                    return $this->codeModel->provider()->generateRelationField($model, $key, $relation);
                } else {
                    // omit other relations
                    return false; // no output
                }

            }
        }
    }

    public function generateRelationHeader($relationName, $relationInfo, $controller)
    {
        $code = "";
        $code .= "
        echo '<h3>".ucfirst($relationName)." ';
    \$this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'=>'mini',
        'buttons'=>array(
            array(
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
    );
        echo '</h3>'";

        return $code;
    }

    public function generateRelationField(
        $model,
        $relationName,
        $relationInfo,
        $captureOutput = false
    ) {

        if ( $relationInfo[0] == 'CBelongsToRelation'
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
                        'GtcRelation',
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