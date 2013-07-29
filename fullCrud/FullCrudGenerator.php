<?php

Yii::setPathOfAlias("gtc", dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');

// import global helpers
Yii::import('gtc.fullCrud.FullCrudHelper',true);

class FullCrudGenerator extends CCodeGenerator
{

    public $codeModel = 'gtc.fullCrud.FullCrudCode';

    /**
     * The array is used to build the autocomplete field and the
     * list of possible columns in the FullCrud form.
     * @return array key = names and value = attributes of the models
     */
    protected function getModels()
    {
        $codeModel = Yii::createComponent($this->codeModel);
        $models = $codeModel->getModels();
        $models[] = "*";
        return $models;
    }

    protected function getAuthTemplates($template)
    {
        foreach(scandir(Yii::getPathOfAlias("gtc.fullCrud.templates.$template.controller.auth")) AS $file){
            if (substr($file,0,1) === ".") {
                continue;
            }
            $name = str_replace(".php", "", $file);
            $return[$name] = $name;
        }
        return $return;
    }

}

?>
