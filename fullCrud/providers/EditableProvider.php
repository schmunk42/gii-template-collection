<?php

class EditableProvider
{
    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    static public function generateEditableField($modelClass, $column, $controller, $view = false)
    {
        $codeModel = new FullCrudCode();
        $provider = $codeModel->provider();

        if ($column->isForeignKey) {
            return $provider->generateValueField($modelClass, $column, $view);
        } else {
            return "array(
            'class' => 'editable.EditableColumn',
            'name' => '{$column->name}',
            'editable' => array(
                'url' => \$this->createUrl('{$controller}/editableSaver'),
                'placement' => 'right',
            )
        )";
        }
    }

}