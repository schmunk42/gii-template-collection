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
    public $moduleName;
    // Slim template
    public $authTemplateSlim = "yii_user_management_access_control";
    // Hybrid template
    public $authTemplateHybrid = "yii_user_management_access_control";
    public $formOrientation = "horizontal";
    public $textEditor = "textarea";
    public $backendThemeViewPath = "application.themes.backend.views";
    public $frontendThemeViewPath = "application.themes.frontend.views";
    // Legacy template
    public $authTemplate = "auth_filter_default";

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                 array('validation', 'required'),
                 array('authTemplateSlim, authTemplateHybrid, authTemplate, identificationColumn, formOrientation, textEditor, backendThemeViewPath, frontendThemeViewPath', 'safe'),
                 array(
                     'messageCatalog, moduleName',
                     'match',
                     'pattern' => '/^[a-zA-Z_][\w.]*$/',
                     'message' => '{attribute} should only contain word characters.'
                 ),
                 array('moduleName', 'sticky'),
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

    // updated for $moduleName handling
    public function getModule()
    {
        if (!empty($this->moduleName)) {
            if (($module = Yii::app()->getModule($this->moduleName)) !== null) {
                return $module;
            }
        }
        return parent::getModule();
    }

    // updated for $moduleName handling
    public function getControllerID()
    {
        if ($this->getModule() !== Yii::app() && !empty($this->moduleName)) {
            return $this->controller;
        } else {
            return parent::getControllerID();
        }
    }

    // updated for $moduleName handling
    public function successMessage()
    {
        $link = CHtml::link(
            'try it now',
            Yii::app()->createUrl($this->moduleName . '/' . $this->controller),
            array('target' => '_blank')
        );
        return "The controller has been generated successfully. You may $link.";
    }

    /**
     * Returns relations of current model
     * @return array
     */
    public function getRelations()
    {
        return CActiveRecord::model($this->modelClass)->relations();
    }

    public function requiredTemplates()
    {
        return array(
            'controller/controller.php',
        );
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

        // Adapted code from original CrudCode->prepare() to support a more flexible template structure
        $this->files=array();
        $templatePath=$this->templatePath;

        // Add the controller view manually
        $controllerTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'controller.php';
        $this->files[]=new CCodeFile(
            $this->controllerFile,
            $this->render($controllerTemplateFile)
        );

        // Add remaining files recursively
        $this->addFilesFromPath($templatePath, null);

    }

    /**
     * Add files from the specified path
     * One exception: We ignore the controller directory since it is
     * already rendered manually
     * @param $templatePath
     * @param $viewPath
     */
    protected function addFilesFromPath($templatePath, $viewPathAlias)
    {
        // Default target view path for view files in the template root directory
        if (is_null($viewPathAlias)) {
            $viewPath = $this->getViewPath();
        } else {
            $viewPath = Yii::getPathOfAlias($viewPathAlias);
        }

        $files = scandir($templatePath);
        foreach ($files as $file) {
            $filePath = $templatePath . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath) && CFileHelper::getExtension($file) === 'php') {

                $this->files[] = new CCodeFile(
                    $viewPath . DIRECTORY_SEPARATOR . $this->getControllerID() . DIRECTORY_SEPARATOR . $file,
                    $this->render($filePath)
                );

            } elseif ($file !== "." && $file !== ".." && $file !== "controller" && is_dir($filePath)) {

                // Decide the target path alias of the directory
                if ($file == "_backend") {
                    $viewPathAlias = $this->backendThemeViewPath;
                } elseif ($file == "_frontend") {
                    $viewPathAlias = $this->frontendThemeViewPath;
                } else {
                    $viewPathAlias = (is_null($viewPathAlias) ? '' : $viewPathAlias . ".") . $file;
                }

                $this->addFilesFromPath($filePath, $viewPathAlias);
            }
        }

        }

    /**
     * Overridden not to supply controller id, which we do manually
     * @return string
     */
    public function getViewPath()
    {
        return $this->getModule()->getViewPath();
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

    public function getItemLabel($model = null)
    {
        if ($model === null) {
            $model = $this->model;
        }
        return FullCrudHelper::suggestIdentifier($model);
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
