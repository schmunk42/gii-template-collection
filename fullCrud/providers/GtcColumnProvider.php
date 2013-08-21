<?php

class GtcColumnProvider extends GtcCodeProvider
{
    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    public function generateColumn($modelClass, $column, $view = false) // TODO: remove view?
    {
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

            if ($view === true) {
                return "array(
                    'name'=>'{$column->name}',
                    'value'=>CHtml::value(\$model,'{$relname}." . $suggestIdentifier . "'),
                    )";
            } elseif ($view == 'search') {
                return "echo \$form->dropDownList(\$model,'{$column->name}',CHtml::listData({$relatedModelName}::model()->findAll(),
                '{$fcolumns[0]}', '{$fcolumns[1]}'),array('prompt'=>'all'))";
            } else {
                return "array(
                    'name'=>'{$column->name}',
                    'value'=>'CHtml::value(\$data,\'{$relname}." . $suggestIdentifier . "\')',
                            'filter'=>CHtml::listData({$relatedModelName}::model()->findAll(array('limit'=>1000)), '{$fcolumns[0]}', '{$suggestIdentifier}'),
                            )";
            }
        } elseif (strtoupper($column->dbType) == 'TEXT') {
            return "#'{$column->name}'"; // comment text fields
        } elseif (strtoupper($column->dbType) == 'BOOLEAN'
            or strtoupper($column->dbType) == 'TINYINT(1)' or
            strtoupper($column->dbType) == 'BIT'
        ) {
            if ($view) {
                return "array(
                    'name'=>'{$column->name}',
                    'value'=>\$model->{$column->name}?\'yes\':\'no\',
                )";
            } else {
                return "array(
                    'name'=>'{$column->name}',
                    'value'=>'\$data->{$column->name}?\'yes\':\'no\'',
                    'filter'=>array('0'=>'no','1'=>'yes'),
                )";
            }
        } else { // TODO: useful?, needed?
            if ($column->name == 'createtime'
                or $column->name == 'updatetime'
                or $column->name == 'timestamp'
            ) {
                return "array(
                'name'=>'{$column->name}',
                'value' =>'date(\"Y. m. d G:i:s\", \$data->{$column->name})')";
            } else {
                return ("'" . $column->name . "'");
            }
            #}
        }
    }

}