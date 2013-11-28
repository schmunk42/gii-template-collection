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

    public function generateColumn($modelClass, $column, $view = false) // TODO: remove view?
    {
        if (substr(strtoupper($column->dbType), 0, 4) != 'ENUM') {
            return NULL;
        }

        return "array(
                'class' => 'editable.EditableColumn',
                'name' => '{$column->name}',
                'value' => '\$data->getEnumLabel(\'{$column->name}\',\$data->{$column->name})',
                'editable' => array(
                    'type' => 'select',
                    'url' => \$this->createUrl('/{$this->codeModel->controller}/editableSaver'),
                    'source' => \$model->getEnumFieldValuetext('{$column->name}'),
                    //'placement' => 'right',
                ),
               'filter' => \$model->getEnumFieldLabels('{$column->name}'),
            )";

    }


}
