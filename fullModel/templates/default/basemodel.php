<?php
/**
 * This is the template for generating the model class of a specified table.
 * In addition to the default model Code, this adds the GtcSaveRelationsBehavior
 * to the model class definition.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * This is the model base class for the table "<?php echo $tableName; ?>".
 *
 * Columns in table "<?php echo $tableName; ?>" available as properties of the model:
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
 *
<?php if(count($relations)>0): ?>
 * Relations of table "<?php echo $tableName; ?>" available as properties of the model:
<?php else: ?>
 * There are no model relations.
<?php endif; ?>
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
    if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
    }
    ?>
<?php endforeach; ?>
 */
abstract class <?php echo 'Base' . $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
<?php
    $is_enum_comment = FALSE;
    foreach ($columns as $column) {
        if (substr(strtoupper($column->dbType), 0, 4) == 'ENUM') {
            $enum_values = explode(',', substr($column->dbType, 4, strlen($column->dbType) - 1));

            foreach ($enum_values as $value) {
                if(!$is_enum_comment){
?>
    /**
    * ENUM field values as constants
    */
<?php

                    $is_enum_comment = TRUE;
                }
                $value = trim($value, "()'");
                $const_name = strtoupper($column->name . '_' . $value);
                $const_name = preg_replace('/\s+/','_',$const_name);
                $const_name = str_replace(array('-','_',' '),'_',$const_name);
				$const_name=preg_replace('/[^A-Z0-9_]/', '', $const_name);

                echo '    const ' . $const_name . ' = \'' . $value . '\';' . PHP_EOL;
            }
        }         
    }
?>

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '<?php echo $tableName; ?>';
    }

    public function rules()
    {
        return array_merge(
            parent::rules(), array(
<?php
        foreach($rules as $rule) {
            echo "                $rule,\n";
        }
?>
                array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on' => 'search'),
            )
        );
    }

    public function getItemLabel()
    {
        return (string) $this-><?php
            $found = false;
        foreach($columns as $name => $column) {
            if(!$found
                    && $column->type != 'datetime'
                    && $column->type==='string'
                    && !$column->isPrimaryKey) {
                echo $column->name;
                $found = true;
            }
        }

        // if the columns contains no column of type 'string', return the
        // first column (usually the primary key)
        if(!$found)
            echo reset($columns)->name;
        ?>;
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(), array(
                'savedRelated' => array(
                    'class' => '\GtcSaveRelationsBehavior'
                )
            )
        );
    }

    public function relations()
    {
        return array_merge(
            parent::relations(), array(
<?php
        foreach($relations as $name=>$relation) {
            echo "                '$name' => $relation,\n";
        }
?>
            )
        );
    }

    public function attributeLabels()
    {
        return array(
<?php
        foreach($labels as $name=>$label) {
            echo "            '$name' => Yii::t('".$this->messageCatalog."', '$label'),\n";
        }
?>
        );
    }
<?php
    $aEnumLabels = array();
    foreach ($columns as $column) {
        
        if (substr(strtoupper($column->dbType), 0, 4) == 'ENUM') {

            $enum_values = explode(',', substr($column->dbType, 4, strlen($column->dbType) - 1));
            $aEnumLabels[$column->name] = array();
            foreach ($enum_values as $value) {

                $value = trim($value, "()'");
                $label = ucwords(trim(strtolower(str_replace(array('-', '_'), ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $value)))));
                $label = preg_replace('/\s+/', ' ', $label);

                $aEnumLabels[$column->name][$value] = $label;
            }

        }
}
if ($aEnumLabels){
?>

    public function enumLabels()
    {
        return array(
<?php
    foreach($aEnumLabels as $sColumnName => $aColumn){
        echo "           '$sColumnName' => array(" . PHP_EOL;
        foreach($aColumn as $value => $label){
            echo "               '$value' => Yii::t('" . $this->messageCatalog . "', '$label')," . PHP_EOL;
        }
        echo "           )," . PHP_EOL;

    }
?>
            );
    }

    public function getEnumFieldLabels($column){

        $aLabels = $this->enumLabels();
        return $aLabels[$column];
    }

    public function getEnumLabel($column,$value){

        $aLabels = $this->enumLabels();

        if(!isset($aLabels[$column])){
            return $value;
        }

        if(!isset($aLabels[$column][$value])){
            return $value;
        }

        return $aLabels[$column][$value];
    }

<?php
}

?>

    public function searchCriteria($criteria = null)
    {
        if (is_null($criteria)) {
            $criteria = new CDbCriteria;
        }

<?php
    foreach($columns as $name=>$column)
    {
        if($column->type==='string' and !$column->isForeignKey)
        {
            echo "        \$criteria->compare('t.$name', \$this->$name, true);\n";
        }
        else
        {
            echo "        \$criteria->compare('t.$name', \$this->$name);\n";
        }
    }
    echo "\n";
?>

        return $criteria;

    }

}
