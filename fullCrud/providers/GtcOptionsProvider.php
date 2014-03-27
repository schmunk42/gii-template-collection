<?php

class GtcOptionsProvider extends GtcCodeProvider
{
    public function generateActiveField($model, $column)
    {
        if ($column->autoIncrement) {
            return false;
        }

        // Render a dropdown list if the model has a method optsColumn().
        $func = "opts" . str_replace("_", "", $column->name);
        if (method_exists($model, $func)) {
            return "echo \$form->dropDownList(\$model,'{$column->name}',{$model}::{$func}(),array('empty'=>'undefined'));";
        }
    }

    public function generateAttribute($model, $column)
    {
        // Render a dropdown list if the model has a method optsColumn().
        $func = 'opts' . str_replace("_", "", $column->name);

        $code = null;
        if ($column->isForeignKey) {

        } elseif ($column->name) {
            $modelName = Yii::import($model);
            if (method_exists($modelName, $func)) {
                $code = "array(
                        'name'=>'{$column->name}',
                        'type' => 'raw',
                        'value' =>\$this->widget(
                            'TbEditableField',
                            array(
                                'model'=>\$model,
                                'emptytext' => 'Click to select',
                                'type' => 'select',
                                'source' => {$modelName}::{$func}(),
                                'attribute'=>'{$column->name}',
                                'url' => \$this->createUrl('/{$this->codeModel->controller}/editableSaver'),
                                'select2' => array(
                                    'placeholder' => 'Select...',
                                    'allowClear' => true
                                )
                            ),
                            true
                        )
                    ),\n";
            }
        }
        return $code;
    }

}