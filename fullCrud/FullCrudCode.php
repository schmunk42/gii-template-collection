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
    public $messageCatalog = "crud";
    public $template = "slim";
    // Slim template
    public $authTemplateSlim = "yii_user_management_access_control";
    // Hybrid template
    public $authTemplateHybrid = "yii_user_management_access_control";
    public $formOrientation = "horizontal";
    public $textEditor = "textarea";
    public $backendViewPathAlias = "application.themes.backend2.views";
    public $frontendViewPathAlias = "application.themes.frontend.views";
    // Legacy template
    public $authTemplate = "auth_filter_default";

    /*
     * for usage as provider
     */
    public $codeModel;

    /*
     * custom providers, topmost has highest priority
     */
    public $providers = array();

    private $_defaultProviders = array(
        "gtc.fullCrud.providers.GtcIdentifierProvider",
        "gtc.fullCrud.providers.GtcPartialViewProvider",
        "gtc.fullCrud.providers.EditableProvider",
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
                 array('authTemplateSlim, authTemplateHybrid, providers, authTemplate, formOrientation, textEditor, backendViewPathAlias, frontendViewPathAlias', 'safe'),
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
        return "echo " . parent::generateActiveLabel($modelClass, $column);
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
        return "echo " . parent::generateActiveField($modelClass, $column);
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
        return str_replace(" ",".",ucwords(str_replace("/"," ",$this->getModule()->id.'/'.$this->getControllerID())));
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
