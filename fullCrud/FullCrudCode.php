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

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                 array('validation, authTemplate', 'required'),
                 array('identificationColumn', 'safe'),
                 array('messageCatalog', 'match', 'pattern' => '/^[a-zA-Z_][\w.]*$/',
                       'message' => '{attribute} should only contain word characters.'),
            )
        );
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
    // a model ? 
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



    private function getOutputViewDirectory(){
        $controllerDir = dirname($this->files[0]->path);
        $controllerName = strtolower(basename(str_replace('Controller','',$this->files[0]->path), ".php"));
        $viewDir = str_replace('controllers','views/'.$controllerName, $controllerDir);
        return $viewDir;
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
         * and CrudFieldProviders from gtc together.
         */

        // detect relation column
        foreach ($this->getRelations() as $key => $relation) {
            if ($relation[2] == $column->name) {
                return $this->generateRelationRow($modelClass, $column, $key, $relation);
            }
        }

        if ($column->type === 'boolean')
            return "\$form->checkBoxRow(\$model,'{$column->name}')";
        else if (stripos($column->dbType,'text') !== false)
            return "\$form->textAreaRow(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50, 'class'=>'span8'))";
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

    public function generateRelationRow($modelClass, $column, $relationName, $relationInfo) {

        if ($columns = CActiveRecord::model($relationInfo[1])->tableSchema->columns) {

            $suggestedfield = FullCrudCode::suggestName($columns);
            $field          = current($columns);
            $style          = $relationInfo[0] == 'CManyManyRelation' ? 'multiselect' : 'dropdownlist';

            if (is_object($field)) {
                if ($relationInfo[0] == 'CManyManyRelation') {
                    return $this->codeProvider->generateRelation($model=$modelClass, $relationName, $relationInfo);
                }
                elseif ($relationInfo[0] == 'CHasOneRelation') {
                    return $this->codeProvider->generateRelation($model=$modelClass, $relationName, $relationInfo);
                }

                $allowEmpty = (CActiveRecord::model($modelClass)->tableSchema->columns[$relationInfo[2]]->allowNull ?
                    'true' : 'false');

                $inputField = 'relationRow';
                return "\$form->{$inputField}(\$model,'{$column->name}',array(
							'model' => \$model,
							'relation' => '{$relationName}',
							'fields' => '{$suggestedfield}',
							'allowEmpty' => {$allowEmpty},
							'style' => '{$style}',
							'htmlOptions' => array(
								'checkAll' => 'all',
							),
						))";
            }
        }
    }

}

?>
