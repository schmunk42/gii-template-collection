<?php

class YiiBoosterActiveRowProvider extends GtcCodeProvider
{
    public function generateActiveRow($modelClass, $column, $relation = false)
    {

        /*
         * TODO: Evaluate how to utilize the best from TbActiveForm (using type attribute + TbFormInputElement::$tbActiveFormMethods)
         * TODO: This should be moved to providers, see @link generateActiveField
         * and CrudFieldProviders from gtc together.
         */

        if ($column->type === 'boolean') {
            return "\$form->checkBoxRow(\$model,'{$column->name}')";
        } else {
            if (stripos($column->dbType, 'text') !== false) {

                switch ($this->codeModel->textEditor) {
                    default:
                    case "textarea":
                        return "\$form->textAreaRow(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50, 'class'=>'span8'))";
                        break;
                    case "redactor":
                        return "\$form->redactorRow(\$model, '{$column->name}', array('rows'=>6, 'cols'=>50, 'class'=>'span8'))";
                        break;
                    case "html5Editor":
                        return "\$form->html5EditorRow(\$model, '{$column->name}', array('rows'=>6, 'cols'=>50, 'class'=>'span8', 'options' => array(
                    'link' => true,
                    'image' => false,
                    'color' => false,
                    'html' => true,
            )))";
                        break;
                    case "ckEditor":
                        return "\$form->ckEditorRow(\$model, '{$column->name}', array('options'=>array('fullpage'=>'js:true', 'width'=>'640', 'resize_maxWidth'=>'640','resize_minWidth'=>'320')))";
                        break;
                    case "markdownEditor":
                        return "\$form->markdownEditorRow(\$model, '{$column->name}', array('rows'=>6, 'cols'=>50, 'class'=>'span8'))";
                        break;
                }

            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'passwordFieldRow';
                } else {
                    $inputField = 'textFieldRow';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    return "\$form->{$inputField}(\$model,'{$column->name}')";
                } else {
                    return "\$form->{$inputField}(\$model,'{$column->name}',array('maxlength'=>$column->size))";
                }
            }
        }
    }
}