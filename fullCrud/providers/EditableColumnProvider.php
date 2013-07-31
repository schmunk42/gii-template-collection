<?php

class EditableColumnProvider extends GtcCodeProvider
{
    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    public function generateColumn($modelClass, $column)
    {
        $provider = $this->codeModel->provider();

        if ($column->isForeignKey) {
            return null; //$provider->generateValueField($modelClass, $column);
        } else {
            return "array(
            'class' => 'editable.EditableColumn',
            'name' => '{$column->name}',
            'editable' => array(
                'url' => \$this->createUrl('/{$this->codeModel->controller}/editableSaver'),
                'placement' => 'right',
            )
        )";
        }
    }

}