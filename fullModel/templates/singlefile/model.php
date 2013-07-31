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
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass; ?>
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '<?php echo $tableName; ?>';
    }

    public function rules()
    {
        return array(
                <?php
                foreach($rules as $rule) {
                echo "            $rule,\n";
                }
                ?>
                array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
                );
    }

    public function relations()
    {
        return array(
                <?php
                foreach($relations as $name=>$relation) {
                echo "            '$name' => $relation,\n";
                }
                ?>
                );
    }

    public function attributeLabels()
    {
        return array(
                <?php
                foreach($labels as $name=>$label) {
                echo "            '$name' => Yii::t('app', '$label'),\n";
                }
                ?>
                );
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        <?php
            foreach($columns as $name=>$column)
            {
                if($column->type==='string' and !$column->isForeignKey)
                {
                    echo "        \$criteria->compare('$name', \$this->$name, true);\n";
                }
                else
                {
                    echo "        \$criteria->compare('$name', \$this->$name);\n";
                }
            }
        echo "\n";
        ?>
            return new CActiveDataProvider(get_class($this), array(
                        'criteria'=>$criteria,
                        ));
    }

    public function behaviors() 
    {
        <?php echo $this->getBehaviors($columns); ?>
  }

    public function beforeValidate() 
    {
        return parent::beforeValidate();    
    }

    public function afterValidate() 
    {
        return parent::afterValidate();    
    }

    public function beforeSave() 
    {
        return parent::beforeSave();    
    }

    public function afterSave() 
    {
        return parent::afterSave();    
    }
}
