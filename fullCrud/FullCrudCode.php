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
    public $authTemplate;

    public function prepare()
    {
        $this->codeProvider = new CodeProvider;
        if (!$this->identificationColumn) {
            $this->identificationColumn = $this->tableSchema->primaryKey;
        }

        if (!array_key_exists(
            $this->identificationColumn, $this->tableSchema->columns)
        ) {
            $this->addError('identificationColumn',
                            'The specified column can not be found in the models attributes. <br /> Please specify a valid attribute. If unsure, leave the field empty.');
        }
        parent::prepare();
    }

    public function rules()
    {
        return array_merge(parent::rules(),
                           array(
                                array('validation, authTemplate', 'required'),
                                array('identificationColumn', 'safe'),
                           ));
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

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
                           array(
                                'validation' => 'Validation method',
                           ));
    }

    public function init()
    {
        parent::init();
    }

    // Which column will most probably be the one that gets used to list
    // a model ? It may be the first non-numeric column.
    public static function suggestName($columns)
    {
        foreach ($columns as $column) {
            if ($column->isPrimaryKey) {
                $fallbackName = $column->name;
            }
            if (!$column->isForeignKey
                && !$column->isPrimaryKey
                && $column->type != 'INT'
                && $column->type != 'INTEGER'
                && $column->type != 'BOOLEAN'
            ) {
                return $column->name;
                break;
            }
        }
        return $fallbackName;
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
}

?>
