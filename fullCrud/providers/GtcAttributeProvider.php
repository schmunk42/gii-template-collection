<?php

class GtcAttributeProvider extends GtcCodeProvider
{
    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    public function generateAttribute($modelClass, $column, $view = false)
    {
        $code = "";
        if ($column->isForeignKey) {
            $code .= "        array(\n";
            $code .= "            'name'=>'{$column->name}',\n";
            foreach ($this->codeModel->relations as $key => $relation) {
                if ((($relation[0] == "CHasOneRelation") || ($relation[0] == "CBelongsToRelation")) && $relation[2] == $column->name) {
                    $relatedModel   = CActiveRecord::model($relation[1]);
                    $suggestedfield = $this->codeModel->provider()->suggestIdentifier($relatedModel);
                    $controller     = $this->codeModel->resolveController($relation);

                    $value = "(\$model->{$key} !== null)?";
                    $value .= "CHtml::link(
                            '<i class=\"icon icon-circle-arrow-left\"></i> '.\$model->{$key}->{$suggestedfield},
                            array('{$controller}/view','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}),
                            array('class'=>'')).";
                    $value .= "' '.";
                    $value .= "CHtml::link(
                            '<i class=\"icon icon-pencil\"></i> ',
                            array('{$controller}/update','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}),
                            array('class'=>''))";

                    $value .= ":'n/a'";

                    $code .= "            'value'=>{$value},\n";
                    $code .= "            'type'=>'html',\n";
                }
            }
            $code .= "        ),\n";
        } elseif (stristr($column->name, 'url')) {
            $code .= "array(";
            $code .= "            'name'=>'{$column->name}',\n";
            $code .= "            'type'=>'url',\n";
            $code .= "),\n";
        } elseif ($column->name == 'createtime'
            or $column->name == 'updatetime'
            or $column->name == 'timestamp'
        ) {
            $code .= "array(
                    'name'=>'{$column->name}',
                    'value' =>\$locale->getDateFormatter()->formatDateTime(\$model->{$column->name}, 'medium', 'medium')),\n";
        } else {
            $code .= "        '" . $column->name . "',\n";
        }
        return $code;
    }
}