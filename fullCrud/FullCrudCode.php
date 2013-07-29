<?php

Yii::import('system.gii.generators.crud.CrudCode');

Yii::setPathOfAlias("gtc", dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
Yii::import('gtc.components.*');
Yii::import('gtc.fullCrud.FullCrudHelper');
Yii::import('gtc.fullCrud.providers.*');

class FullCrudCode extends CrudCode
{
    // validation method; 0 = none, 1 = ajax, 2 = client-side, 3 = both
    public $validation = 3;
    public $identificationColumn = null;
    public $baseControllerClass = 'Controller';
    public $messageCatalog = "crud";
    public $template = "slim";
    // Slim template
    public $authTemplateSlim = "yii_user_management_access_control";
    // Hybrid template
    public $authTemplateHybrid = "yii_user_management_access_control";
    public $formOrientation = "horizontal";
    public $textEditor = "textarea";
    // Legacy template
    public $authTemplate = "auth_filter_default";

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                 array('validation', 'required'),
                 array('authTemplateSlim, authTemplateHybrid, authTemplate, identificationColumn, formOrientation, textEditor', 'safe'),
            )
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            array(
                 'validation' => 'Validation method',
            )
        );
    }

    //
    public function getEnableAjaxValidation()
    {
        return ($this->validation == 1 || $this->validation == 3) ? 'true' : 'false';
    }

    //
    public function getEnableClientValidation()
    {
        return ($this->validation == 2 || $this->validation == 3) ? 'true' : 'false';
    }

    /**
     * Returns relations of current model
     * @return array
     */
    public function getRelations()
    {
        return CActiveRecord::model($this->modelClass)->relations();
    }

    public function getItemLabel($model = null)
    {
        if ($model === null) {
            $model = $this->model;
        }
        return FullCrudHelper::suggestIdentifier($model);
    }

    public function prepare()
    {
        if (!$this->identificationColumn) {
            $this->identificationColumn = $this->tableSchema->primaryKey;
        }

        if (!array_key_exists($this->identificationColumn, $this->tableSchema->columns)) {
            $this->addError(
                'identificationColumn',
                'The specified column can not be found in the models attributes. <br /> Please specify a valid attribute. If unsure, leave the field empty.'
            );
        }

        parent::prepare();
        }

    public function validateModel($attribute, $params)
    {
        // check your import paths, if you get an error here
        // PHP error can't be catched as an exception
        if ($this->model) {
            Yii::import($this->model, true);
        }
        parent::validateModel($attribute, $params);
    }

    // updated for $moduleName handling
    public function successMessage()
    {
        $link = CHtml::link(
            'try it now',
            Yii::app()->createUrl($this->controller),
            array('target' => '_blank')
        );
        return "The controller has been generated successfully. You may $link.";
    }

    /**
     * @param CCodeFile $file whether the code file should be saved
     * @todo Don't use a constant
     */
    public function confirmed($file)
    {
        if (defined('GIIC_ALL_CONFIRMED') && GIIC_ALL_CONFIRMED === true) {
            return true;
        } else {
            return parent::confirmed($file);
        }
    }



    public function resolveController($relation)
    {
        $relatedController = strtolower(substr($relation[1], 0, 1)) . substr($relation[1], 1);
        $controllerName = (strrchr($this->controller,"/")) ? strrchr($this->controller,"/") : $this->controller;
        $return = "/".str_replace($controllerName,'/'.$relatedController,$this->controller);
        return $return;
    }





    // ==========================================
    // TODO: move code below to "providers"
    // ==========================================

    /**
     * Returns the viewFile for the column if exists otherwise it returns null
     * @return string
     * @todo detection
     */
    public function resolveColumnViewFile($column)
    {
        if (!isset($this->files[0])) {
            return null;
        }
        $viewDir   = $this->getOutputViewDirectory();
        $viewAlias = 'columns' . DIRECTORY_SEPARATOR . $column->name;
        $viewFile  = $viewDir . DIRECTORY_SEPARATOR . $viewAlias . '.php';
        return (file_exists($viewFile)) ? $viewAlias : null;
    }

    /**
     * Returns the viewFile for the relation if exists otherwise it returns null
     * @return string
     * @todo detection
     */
    public function resolveRelationViewFile($relation)
    {
        if (!isset($this->files[0])) {
            return null;
        }

        $viewDir   = $viewDir = $this->getOutputViewDirectory();
        $viewAlias = 'relations' . DIRECTORY_SEPARATOR . $relation[1];
        $viewFile  = $viewDir . DIRECTORY_SEPARATOR . $viewAlias . '.php';
        return (file_exists($viewFile)) ? $viewAlias : null;
    }


    /**
     * Prepend code fragments from parent class with an echo
     *
     * @param $modelClass
     * @param $column
     *
     * @return string
     */
    public function generateActiveLabel($modelClass, $column)
    {
        return "echo " . parent::generateActiveLabel($modelClass, $column);
    }

    /**
     * Get input field from provider
     *
     * @param $modelClass
     * @param $column
     *
     * @return string
     */
    public function generateActiveField($modelClass, $column)
    {
        $providers = array(
            "gtc.fullCrud.providers.P3CrudFieldProvider",
            "gtc.fullCrud.providers.FullCrudFieldProvider",
            "system.gii.generators.crud.CrudCode",
        );
        foreach ($providers AS $provider) {
            $class = Yii::import($provider);
            if (method_exists($class, "generateActiveField")) {
                if ($class::generateActiveField($modelClass, $column) !== null) {
                    if ($provider === "system.gii.generators.crud.CrudCode") {
                        return "echo " . $class::generateActiveField($modelClass, $column);
                    } else {
                        return $class::generateActiveField($modelClass, $column);
                    }

                }
            }
        }
    }

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

                switch ($this->textEditor) {
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

    public function generateRelationHeader($model, $relationName, $relationInfo)
    {
        $controller = self::resolveController($relationInfo); // TODO
        $code       = "";
        $code .= "
    \$this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array(
                'label'=>'" . ucfirst($relationName) . "',
                'icon'=>'icon-list-alt',
                'url'=> array('/{$controller}/admin')
            ),
            array(
                'icon'=>'icon-plus',
                'url'=>array(
                    '/{$controller}/create',
                    '{$relationInfo[1]}' => array('{$relationInfo[2]}'=>\$model->{\$model->tableSchema->primaryKey})
                    )
                ),
            ),
        )
    );";

        return $code;
    }



    private function getOutputViewDirectory()
    {
        $controllerDir  = dirname($this->files[0]->path);
        $controllerName = strtolower(basename(str_replace('Controller', '', $this->files[0]->path), ".php"));
        $viewDir        = str_replace('controllers', 'views/' . $controllerName, $controllerDir);
        return $viewDir;
    }

}

?>
