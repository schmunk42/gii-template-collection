<?php

Yii::setPathOfAlias("gtc", dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');

class FullCrudGenerator extends CCodeGenerator
{

    public $codeModel = 'gtc.fullCrud.FullCrudCode';

    /**
     * Get auth templates for main template
     *
     * @param $template
     *
     * @return mixed
     */
    protected function getAuthTemplates($template)
    {
        foreach (scandir(Yii::getPathOfAlias("gtc.fullCrud.templates.$template.auth")) AS $file) {
            if (substr($file, 0, 1) === ".") {
                continue;
            }
            $name          = str_replace(".php", "", $file);
            $return[$name] = $name;
        }
        return $return;
    }

    /**
     * Returns the model names and, if possible, the attributes in an array.
     * Only non abstract and superclasses of CActiveRecord models are returned.
     * The array is used to build the autocomplete field and the
     * list of possible columns in the FullCrud form.
     * @return array key = names and value = attributes of the models
     */
    protected function getModels()
    {
        $models    = array();
        $aliases   = array();
        $aliases[] = 'application.models';
        foreach (Yii::app()->getModules() as $moduleName => $config) {
            if ($moduleName != 'gii') {
                $aliases[] = $moduleName . ".models";
            }
        }

        foreach ($aliases as $alias) {
            if (!is_dir(Yii::getPathOfAlias($alias))) {
                continue;
            }
            $files = scandir(Yii::getPathOfAlias($alias));
            Yii::import($alias . ".*");
            foreach ($files as $file) {
                if ($fileClassName = $this->checkFile($file, $alias)) {
                    $classname = sprintf('%s.%s', $alias, $fileClassName);
                    Yii::import($classname);
                    try {
                        if (!class_exists($fileClassName)) {
                            throw new Exception('Model ' . $fileClassName . ' does not exist');
                        }

                        $model = new $fileClassName;
                        if (is_object($model) && $model->getMetaData()) {
                            $models[$classname] = $model->attributes;
                        } else {
                            $models[$classname] = array();
                        }
                    } catch (ErrorException $e) {
                        break;
                    } catch (CDbException $e) {
                        break;
                    } catch (Exception $e) {
                        break;
                    }
                }
            }
        }

        return $models;
    }


    /**
     * Internal function
     *
     * @param        $file
     * @param string $alias
     *
     * @return null|string
     */
    private function checkFile($file, $alias = '')
    {
        // find models, exclude base model
        if (substr($file, 0, 1) !== '.'
            && substr($file, 0, 2) !== '..'
            && substr($file, 0, 4) !== 'Base'
            && strtolower(substr($file, -4)) === '.php'
        ) {
            $fileClassName = substr($file, 0, strpos($file, '.'));
            if (class_exists($fileClassName)
                && is_subclass_of($fileClassName, 'CActiveRecord')
            ) {
                $fileClass = new ReflectionClass($fileClassName);
                // exclude abstract classes
                if ($fileClass->isAbstract()) {
                    return null;
                } else {
                    return $models[] = $fileClassName;
                }
            }
        }
    }
}

?>
