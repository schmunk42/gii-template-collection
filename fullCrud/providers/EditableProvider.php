<?php

class EditableProvider extends GtcCodeProvider
{
    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    public function generateEditableField($modelClass, $column, $controller, $view = false)
    {
        $provider = $this->codeModel->provider();

        if (!$column->isForeignKey) {
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