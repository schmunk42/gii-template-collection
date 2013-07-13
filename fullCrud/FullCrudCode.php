<?php

Yii::import('system.gii.generators.crud.CrudCode');

Yii::setPathOfAlias("gtc", dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
Yii::import('gtc.components.*');
Yii::import('gtc.fullCrud.CodeProvider');
Yii::import('gtc.fullCrud.providers.*');

class FullCrudCode extends CrudCode
{
    // validation method; 0 = none, 1 = ajax, 2 = client-side, 3 = both
    public $validation = 3;
    public $identificationColumn = null;
    public $baseControllerClass = 'Controller';
    public $codeProvider;
    public $authTemplate = "yii_user_management_access_control";
    public $messageCatalog = "crud";
    public $template = "slim";
    public $formOrientation = "horizontal";
    public $textEditor = "html5Editor";
    public $moduleName;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                 array('validation, authTemplate', 'required'),
                 array('identificationColumn', 'safe'),
                 array('messageCatalog, moduleName', 'match', 'pattern' => '/^[a-zA-Z_][\w.]*$/',
                       'message' => '{attribute} should only contain word characters.'),
                 array('moduleName', 'sticky'),
            )
        );
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            array(
                 'validation' => 'Validation method',
            ));
    }

    // updated for $moduleName handling
    public function getModule(){
        if (!empty($this->moduleName)) {
            if(($module=Yii::app()->getModule($this->moduleName))!==null) {
                return $module;
            }
        }
        return parent::getModule();
    }

    // updated for $moduleName handling
    public function getControllerID()
    {
        if($this->getModule()!==Yii::app() && !empty($this->moduleName))
            return $this->controller;
        else
            return parent::getControllerID();
    }

    // updated for $moduleName handling
    public function successMessage()
    {
        $link=CHtml::link('try it now', Yii::app()->createUrl($this->moduleName.'/'.$this->controller), array('target'=>'_blank'));
        return "The controller has been generated successfully. You may $link.";
    }

    /**
     * Returns relations of current model
     *
     * @return array
     */
    public function getRelations()
    {
        return CActiveRecord::model($this->modelClass)->relations();
    }



    private function getOutputViewDirectory(){
        $controllerDir = dirname($this->files[0]->path);
        $controllerName = strtolower(basename(str_replace('Controller','',$this->files[0]->path), ".php"));
        $viewDir = str_replace('controllers','views/'.$controllerName, $controllerDir);
        return $viewDir;
    }


    public function prepare()
    {
        $this->codeProvider = new CodeProvider;
        if (!$this->identificationColumn) {
            $this->identificationColumn = $this->tableSchema->primaryKey;
        }

        if (!array_key_exists($this->identificationColumn, $this->tableSchema->columns)) {
            $this->addError('identificationColumn',
                            'The specified column can not be found in the models attributes. <br /> Please specify a valid attribute. If unsure, leave the field empty.');
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


    // TODO: this should be deprecated --> moved to CodeProvider
    public static function suggestName($columns)
    {
        $nonNumericFound = false;
        foreach ($columns as $column) {
            if ($column->isPrimaryKey) {
                $fallbackName = $column->name;
            }
            // Use the first non-numeric column as a fallback
            if (!$column->isForeignKey
                && !$column->isPrimaryKey
                && $column->type != 'BIGINT'
                && $column->type != 'INT'
                && $column->type != 'INTEGER'
                && $column->type != 'BOOLEAN'
                && !$nonNumericFound
            ) {
                $fallbackName = $column->name;
                $nonNumericFound = true;
            }
            // Return the first title, name, label column, if found
            if (in_array($column->name, array(
                "title",
                "name",
                "label",
            ))) {
                $fallbackName = $column->name;
                break;
            }
        }
        return $fallbackName;
    }


    /**
     * Returns the viewFile for the column if exists otherwise it returns null
     *
     * @return string
     * @todo detection
     */
    public function resolveColumnViewFile($column)
    {
        if(!isset($this->files[0])) {
            return null;
        }
        $viewDir = $this->getOutputViewDirectory();
        $viewAlias = 'columns'.DIRECTORY_SEPARATOR.$column->name;
        $viewFile = $viewDir.DIRECTORY_SEPARATOR.$viewAlias.'.php';
        return (file_exists($viewFile))?$viewAlias:null;
    }

    /**
     * Returns the viewFile for the relation if exists otherwise it returns null
     *
     * @return string
     * @todo detection
     */
    public function resolveRelationViewFile($relation)
    {
        if(!isset($this->files[0])) {
            return null;
        }

        $viewDir = $viewDir = $this->getOutputViewDirectory();
        $viewAlias = 'relations'.DIRECTORY_SEPARATOR.$relation[1];
        $viewFile = $viewDir.DIRECTORY_SEPARATOR.$viewAlias.'.php';
        return (file_exists($viewFile))?$viewAlias:null;
    }

    /**
     * Prepend code fragments from parent class with an echo
     *
     * @param $modelClass
     * @param $column
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
                    }
                    else {
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

        if ($column->type === 'boolean')
            return "\$form->checkBoxRow(\$model,'{$column->name}')";
		else if (stripos($column->dbType,'text') !== false) {

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

		}
        else
        {
            if (preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
                $inputField='passwordFieldRow';
            else
                $inputField='textFieldRow';

            if ($column->type!=='string' || $column->size===null)
                return "\$form->{$inputField}(\$model,'{$column->name}')";
            else
                return "\$form->{$inputField}(\$model,'{$column->name}',array('maxlength'=>$column->size))";
        }
    }

}

?>
