<?php

class TbEditableProvider extends GtcCodeProvider
{
    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    public function generateColumn($modelClass, $column, $controller = null)
    {
        if (is_null($controller)) {
            $controller = $this->codeModel->controller;
        }
        
        if ($column->isForeignKey) {

            $suggestIdentifier = $this->codeModel->provider()->suggestIdentifier($modelClass);
            $model             = CActiveRecord::model($modelClass);
            $table             = $model->getTableSchema();
            $fk                = $table->foreignKeys[$column->name];
            
            // We have to look into relations to find the correct model class (i.e. if models are generated with table prefix)
            foreach ($model->relations() as $key => $value) {
                // omit relations with array definition
                if (is_array($value[2])) {
                    continue;
                }

                if (strcasecmp($value[2], $column->name) == 0) {
                    $relname  = $key;
                    $relation = $value;
                }
            }

            if (!isset($relation)) {
                return "'" . $column->name . "'";
            }   

            $relatedModel     = CActiveRecord::model($relation[1]);
            $relatedModelName = $relation[1];
            $fcolumns         = $relatedModel->attributeNames();            
            
            //return null; //$provider->generateValueField($modelClass, $column);

            return "array(
                'class' => 'TbEditableColumn',
                'name' => '{$column->name}',
                'value' => 'CHtml::value(\$data, \'{$relname}." . $suggestIdentifier . "\')',                    
                'editable' => array(
                    'type' => 'select',
                    'url' => \$this->createUrl('/{$controller}/editableSaver'),
                    'source' => CHtml::listData({$relatedModelName}::model()->findAll(array('limit' => 1000)), '{$fcolumns[0]}', '{$suggestIdentifier}'),                        
                    //'placement' => 'right',
                )
            )";
            
        } elseif (strtoupper($column->dbType) == 'TEXT') {
            return "#'{$column->name}'"; // comment text fields
        } else {
            return "array(
                'class' => 'TbEditableColumn',
                'name' => '{$column->name}',
                'editable' => array(
                    'url' => \$this->createUrl('/{$controller}/editableSaver'),
                    //'placement' => 'right',
                )
            )";
        }
    }

    public function generateAttribute($modelClass, $column, $view = false)
    {
        if ($column->isForeignKey) {
            return null;
        } elseif ($column->name) {
            $code = "array(
                        'name' => '{$column->name}',
                        'type' => 'raw',
                        'value' => \$this->widget(
                            'TbEditableField',
                            array(
                                'model' => \$model,
                                'attribute' => '{$column->name}',
                                'url' => \$this->createUrl('/{$this->codeModel->controller}/editableSaver'),
                            ),
                            true
                        )
                    ),\n";
        } else {
            $code = null;
        }
        return $code;
    }

}