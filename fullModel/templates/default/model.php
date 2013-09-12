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

// auto-loading
Yii::setPathOfAlias('<?php echo $modelClass; ?>', dirname(__FILE__));
Yii::import('<?php echo $modelClass; ?>.*');

class <?php echo $modelClass; ?> extends <?php echo 'Base' . $modelClass."\n"; ?>
{

    // Add your model-specific methods here. This file will not be overriden by gtc except you force it.
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function init()
    {
        return parent::init();
    }

    public function getItemLabel()
    {
        return parent::getItemLabel();
    }

    public function behaviors()
    {
        <?php
        $behaviors = 'return array_merge(
            parent::behaviors(),
            array()
        );';
        echo $behaviors;
        ?>
    }

    public function rules()
    {
        return array_merge(
            parent::rules()
        /* , array(
          array('column1, column2', 'rule1'),
          array('column3', 'rule2'),
          ) */
        );
    }

}
