<?php

class EnumProvider extends GtcCodeProvider {

    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    public function generateAttribute($modelClass, $column, $view = false) {
        $code = "";
        if (substr(strtoupper($column->dbType), 0, 4) != 'ENUM') {
            return NULL;
        }

        return "        array(
                    'name' => '{$column->name}',
                    'value' => \$model->getEnumLabel('{$column->name}',\$model->{$column->name}),
        ),\n";
    }

    public function generateActiveField($model, $column)
    {
        if (substr(strtoupper($column->dbType), 0, 4) != 'ENUM') {
            return NULL;
        }

        return "echo CHtml::activeDropDownList(\$model, '$column->name', \$model->getEnumFieldLabels('$column->name'))";
    }

}
