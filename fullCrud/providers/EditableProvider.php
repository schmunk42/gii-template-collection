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
        if ($column->isForeignKey) {
            return $codeModel->generateValueField($modelClass, $column, $view);
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