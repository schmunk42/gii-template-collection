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
    public $baseControllerClass = 'Controller';
    public $messageCatalogStandard = "crud";
    public $messageCatalog = "model";
    public $template = "slim";
    // Slim template
    public $authTemplateSlim = "yii_extended_user_management_access_control";
    public $formEnctype = null;
    public $formLayout = 'two-columns';
    // Hybrid template
    public $authTemplateHybrid = "yii_user_management_access_control";
    public $formOrientation = "horizontal";
    public $textEditor = "textarea";
    public $internalModels = array();
    public $backendViewPathAlias = "application.themes.backend2.views";
    public $frontendViewPathAlias = "application.themes.frontend.views";
    // Legacy template
    public $authTemplate = "auth_filter_default";

    /*
     * for usage as provider
     */
    public $codeModel;

    /*
     * custom providers, topmost has highest priority, include GtcPartialViewProvider as first if needed
     */
    public $providers = array();
    
    //for editable and simple crud icon
    public $icon   = "";

    private $_defaultProviders = array(
        "gtc.fullCrud.providers.EnumProvider",
        "gtc.fullCrud.providers.GtcIdentifierProvider",
        "gtc.fullCrud.providers.GtcPartialViewProvider", // highest customization level
        "gtc.fullCrud.providers.GtcOptionsProvider",
        "gtc.fullCrud.providers.TbEditableProvider",
        "gtc.fullCrud.providers.YiiBoosterActiveRowProvider",
        "gtc.fullCrud.providers.GtcRelationProvider",
        "gtc.fullCrud.providers.GtcActiveFieldProvider",
        "gtc.fullCrud.providers.GtcAttributeProvider",
        "gtc.fullCrud.providers.GtcColumnProvider",
        "gtc.fullCrud.FullCrudCode",
    );

    /**
     * Returns validation rules
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                 array('validation', 'required'),
                 array('authTemplateSlim, authTemplateHybrid, providers, authTemplate, formEnctype, formLayout, formOrientation, textEditor, internalModels, backendViewPathAlias, frontendViewPathAlias, messageCatalog,messageCatalogStandard,icon', 'safe'),
            )
        );
    }

    /**
     * Returns form attribute labels
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            array(
                 'validation' => 'Validation method',
            )
        );
    }

    /**
     * Tries to find a provider for the called method, continues until a provider returns not NULL
     *
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     */
    public function provider()
    {
        $provider            = new GtcCodeProviderQueue();
        $provider->providers = CMap::mergeArray($this->providers,$this->_defaultProviders);
        $provider->codeModel = $this;
        Yii::log("Provider queue:".CJSON::encode($this->providers));
        return $provider;
    }

    /**
     * This method is overridden to be able to copy certain templates to backend and frontend themes' view directories
     * More specifically, views placed in _frontend will be copied to the destination as specified by $this->frontendViewPathAlias
     * and views placed in _backend will be copied to the destination as specified by $this->backendViewPathAlias
     */
    public function prepare()
    {

        parent::prepare();

        // Add backend theme views
        $templatePath = $this->templatePath. DIRECTORY_SEPARATOR . "_backend";
        if (is_dir($templatePath)) {
            $files=scandir($templatePath);
            foreach($files as $file)
            {
                if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php')
                {
                    $this->files[]=new CCodeFile(
                        Yii::getPathOfAlias($this->backendViewPathAlias).DIRECTORY_SEPARATOR.$this->getControllerID().DIRECTORY_SEPARATOR.$file,
                        $this->render($templatePath.'/'.$file)
                    );
                }
            }
        }

        // Add frontend theme views
        $templatePath = $this->templatePath. DIRECTORY_SEPARATOR . "_frontend";
        if (is_dir($templatePath)) {
            $files=scandir($templatePath);
            foreach($files as $file)
            {
                if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php')
                {
                    $this->files[]=new CCodeFile(
                        Yii::getPathOfAlias($this->frontendViewPathAlias).DIRECTORY_SEPARATOR.$this->getControllerID().DIRECTORY_SEPARATOR.$file,
                        $this->render($templatePath.'/'.$file)
                    );
                }
            }
        }

    }

    /**
     * prepend echo
     *
     * @param $modelClass
     * @param $column
     *
     * @return string
     */
    public function generateActiveLabel($modelClass, $column)
    {
        return "echo " . $this->generateActiveLabelGtcCodeStyle($modelClass, $column);
    }

    /**
     * prepend echo
     *
     * @param $modelClass
     * @param $column
     *
     * @return string
     */
    public function generateActiveField($modelClass, $column)
    {
        return "echo " . $this->generateActiveFieldGtcCodeStyle($modelClass, $column);
    }

    /**
     * Overridden to ensure that output follows GTC preferred code style
     */
    public function generateActiveLabelGtcCodeStyle($modelClass,$column)
    {
        return "\$form->labelEx(\$model, '{$column->name}')";
    }

    /**
     * Overridden to ensure that output follows GTC preferred code style
     */
    public function generateActiveFieldGtcCodeStyle($modelClass,$column)
    {
        if($column->type==='boolean')
            return "\$form->checkBox(\$model, '{$column->name}')";
        elseif(stripos($column->dbType,'text')!==false)
            return "\$form->textArea(\$model, '{$column->name}', array('rows' => 6, 'cols' => 50))";
        else
        {
            if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
                $inputField='passwordField';
            else
                $inputField='textField';

            if($column->type!=='string' || $column->size===null)
                return "\$form->{$inputField}(\$model, '{$column->name}')";
            else
            {
                if(($size=$maxLength=$column->size)>60)
                    $size=60;
                return "\$form->{$inputField}(\$model, '{$column->name}', array('size' => $size, 'maxlength' => $maxLength))";
            }
        }
    }

    /**
     * Shorthand
     * @return string
     */
    public function getEnableAjaxValidation()
    {
        return ($this->validation == 1 || $this->validation == 3) ? 'true' : 'false';
    }

    /**
     * Shorthand
     * @return string
     */
    public function getEnableClientValidation()
    {
        return ($this->validation == 2 || $this->validation == 3) ? 'true' : 'false';
    }

    /**
     * Returns relations of current active record model
     * @return array
     */
    public function getRelations()
    {
        return CActiveRecord::model($this->modelClass)->relations();
    }

    /**
     * Returns the prefix for auth assignments, eg. `Module.Controller.Action`
     * @return mixed
     */
    public function getRightsPrefix(){
        if ($this->getModule() instanceof GiicApplication) {
            $module = '';
        } else {
            $module = $this->getModule()->id;
        }
        #var_dump($module, $this->getModule());exit;
        return str_replace(" ",".",ucwords(trim(str_replace("/"," ",$module.'/'.$this->getControllerID()))));
    }

    /**
     * @param null $model
     *
     * @return mixed
     * @TODO   ?
     */
    public function getItemLabel($model = null)
    {
        if ($model === null) {
            $model = $this->model;
        }
        return $this->suggestIdentifier($model); // TODO ??? see provider
    }

    /**
     * @param CCodeFile $file whether the code file should be saved
     *
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

    /**
     * Returns a controller route for the specified relation.
     * Note: Controllers and models have to be named the same way, eg. model (Foo) -> controller (FooController)
     *
     * @param $relation
     *
     * @return string
     */
    public function resolveController($relation)
    {
        $relatedController = strtolower(substr($relation[1], 0, 1)) . substr($relation[1], 1);
        $controllerName    = (strrchr($this->controller, "/")) ? strrchr($this->controller, "/") : $this->controller;
        $return            = "/" . str_replace($controllerName, '/' . $relatedController, $this->controller);
        return $return;
    }

}

?>
