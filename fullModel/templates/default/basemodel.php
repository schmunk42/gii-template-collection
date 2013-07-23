<?php
/**
 * This is the template for generating the model class of a specified table.
 * In addition to the default model Code, this adds the CSaveRelationsBehavior
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
abstract class <?php echo 'Base' . $modelClass; ?> extends <?php echo $this->baseClass; ?>
{
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
            echo "            $rule,\n";
        }
?>
            array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
            )
        );
    }

    public function getItemLabel() {
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
                'class' => 'gii-template-collection.components.CSaveRelationsBehavior'
            )
            )
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
            echo "            '$name' => Yii::t('".$this->messageCatalog."', '$label'),\n";
        }
?>
        );
    }


    public function search($criteria = null)
    {
        if (is_null($criteria)) {
            $criteria=new CDbCriteria;
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
        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns a model used to populate a filterable, searchable
     * and sortable CGridView with the records found by a model relation.
     *
     * Usage:
     * $relatedSearchModel = $model->getRelatedSearchModel('relationName');
     *
     * Then, when invoking CGridView:
     *     ...
     *         'dataProvider' => $relatedSearchModel->search(),
     *         'filter' => $relatedSearchModel,
     *     ...
     * @returns CActiveRecord
     */
    public function getRelatedSearchModel($name)
    {

        $md = $this->getMetaData();
        if (!isset($md->relations[$name]))
            throw new CDbException(Yii::t('yii', '{class} does not have relation "{name}".', array('{class}' => get_class($this), '{name}' => $name)));

        $relation = $md->relations[$name];
        if (!($relation instanceof CHasManyRelation))
            throw new CException("Currently works with HAS_MANY relations only");

        $className = $relation->className;
        $related = new $className('search');
        $related->unsetAttributes();
        $related->{$relation->foreignKey} = $this->primaryKey;
        if (isset($_GET[$className]))
        {
            $related->attributes = $_GET[$className];
        }
        return $related;
    }

}
