<?php
Yii::import('system.gii.generators.model.ModelCode');
Yii::import('ext.gtc.components.*');

class FullModelCode extends ModelCode
{
    public $tables;
    public $baseClass = 'CActiveRecord';
    public $messageCatalog = 'crud';

    public function init()
    {
        parent::init();

        if (!@class_exists("GtcSaveRelationsBehavior")) {
            throw new CException("Fatal Error: Class 'GtcSaveRelationsBehavior' could not be found in your application! Add 'ext.gtc.components.*' to your import paths.");
        }

    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                 array('messageCatalog', 'match', 'pattern' => '/^[a-zA-Z_][\w.]*$/',
                       'message'                            => '{attribute} should only contain word characters.'),
            ));
    }

    public function prepare()
    {
        parent::prepare();

        $generate_whole_db = false;
        $templatePath      = $this->templatePath;

        if (($pos = strrpos($this->tableName, '.')) !== false) {
            $schema    = substr($this->tableName, 0, $pos);
            $tableName = substr($this->tableName, $pos + 1);
        }
        else {
            $schema    = '';
            $tableName = $this->tableName;
        }
        if ($tableName[strlen($tableName) - 1] === '*') {
            $generate_whole_db = true;
            $this->tables      = Yii::app()->{$this->connectionId}->schema->getTables($schema);
            if ($this->tablePrefix != '') {
                foreach ($this->tables as $i => $table) {
                    if (strpos($table->name, $this->tablePrefix) !== 0) {
                        unset($this->tables[$i]);
                    }
                }
            }
        }
        else {
            $this->tables = array($this->getTableSchema($this->tableName));
        }

        $this->relations = $this->generateRelations();

        foreach ($this->tables as $table) {
            $tableName = $this->removePrefix($table->name);
            $className = $this->generateClassName($table->name);

            $params = array(
                'tableName'  => $schema === '' ? $tableName : $schema . '.' . $tableName,
                'modelClass' => $className,
                'columns'    => $table->columns,
                'labels'     => $this->generateLabels($table),
                'rules'      => $this->generateRules($table),
                'relations'  => isset($this->relations[$className]) ? $this->relations[$className] : array(),
            );

            if ($this->template != 'singlefile') {
                $this->files[] = new CCodeFile(
                    Yii::getPathOfAlias($this->modelPath) . '/' . 'Base' . $className . '.php',
                    $this->render($templatePath . '/basemodel.php', $params)
                );
            }
        }
    }

    public function requiredTemplates()
    {
        if ($this->template == 'singlefile') {
            return array('model.php');
        }
        else {
            return array(
                'model.php',
                'basemodel.php',
            );
        }
    }

    public function getBehaviors($columns)
    {
        $behaviors = 'return array(';
        if (count($this->relations) > 0) {
            $behaviors .= "'GtcSaveRelationsBehavior', array(
                'class' => 'GtcSaveRelationsBehavior'),";
        }

        foreach ($columns as $name => $column) {
            if (in_array($column->name, array(
                                             'create_time',
                                             'createtime',
                                             'created_at',
                                             'createdat',
                                             'changed',
                                             'changed_at',
                                             'updatetime',
                                             'update_time',
                                             'timestamp'))
            ) {
                $behaviors .= sprintf("\n        'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => %s,
                'updateAttribute' => %s,
                    ),\n", $this->getCreatetimeAttribute($columns),
                                      $this->getUpdatetimeAttribute($columns));
                break; // once a column is found, we are done
            }
        }
        foreach ($columns as $name => $column) {
            if (in_array($column->name, array(
                                             'user_id',
                                             'userid',
                                             'ownerid',
                                             'owner_id',
                                             'created_by',
                                             'createdby',
                                             'create_user'))
            ) {
                $behaviors .= sprintf("\n        'OwnerBehavior' => array(
                                'class' => 'OwnerBehavior',
                            'ownerColumn' => '%s',
                                ),\n", $column->name);
                break; // once a column is found, we are done

            }
        }


        $behaviors .= "\n);\n";

        return $behaviors;
    }

    public function generateRules($table)
    {
        $rules     = array();
        $required  = array();
        $null      = array();
        $integers  = array();
        $numerical = array();
        $length    = array();
        $safe      = array();
        foreach ($table->columns as $column) {
            if ($column->isPrimaryKey && $table->sequenceName !== null) {
                continue;
            }
            $r = !$column->allowNull && $column->defaultValue === null;
            if ($r) {
                $required[] = $column->name;
            }
            else {
                $null[] = $column->name;
            }
            if ($column->type === 'integer') {
                $integers[] = $column->name;
            }
            else {
                if ($column->type === 'double') {
                    $numerical[] = $column->name;
                }
                else {
                    if ($column->type === 'string' && $column->size > 0) {
                        $length[$column->size][] = $column->name;
                    }
                    else {
                        if (!$column->isPrimaryKey && !$r) {
                            $safe[] = $column->name;
                        }
                    }
                }
            }

        }
        if ($required !== array()) {
            $rules[] = "array('" . implode(', ', $required) . "', 'required')";
        }
        if ($null !== array()) {
            $rules[] = "array('" . implode(', ', $null) . "', 'default', 'setOnEmpty' => true, 'value' => null)";
        }
        if ($integers !== array()) {
            $rules[] = "array('" . implode(', ', $integers) . "', 'numerical', 'integerOnly' => true)";
        }
        if ($numerical !== array()) {
            $rules[] = "array('" . implode(', ', $numerical) . "', 'numerical')";
        }
        if ($length !== array()) {
            foreach ($length as $len => $cols)
                $rules[] = "array('" . implode(', ', $cols) . "', 'length', 'max' => $len)";
        }
        if ($safe !== array()) {
            $rules[] = "array('" . implode(', ', $safe) . "', 'safe')";
        }


        return $rules;
    }

    function getCreatetimeAttribute($columns)
    {
        foreach (array('create_time', 'createtime', 'created_at', 'createdat', 'timestamp') as $try)
            foreach ($columns as $column)
                if ($try == $column->name) {
                    return sprintf("'%s'", $column->name);
                }

        return 'null';
    }

    function getUpdatetimeAttribute($columns)
    {
        foreach (array('update_time', 'updatetime', 'changed', 'changed_at') as $try)
            foreach ($columns as $column)
                if ($try == $column->name) {
                    return sprintf("'%s'", $column->name);
                }

        return 'null';
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
}

?>
