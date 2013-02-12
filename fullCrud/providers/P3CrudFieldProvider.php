<?php

/*
 */

class P3CrudFieldProvider
{

    static public function generateActiveField($model, $column)
    {

        if (strtoupper($column->dbType) == 'TEXT' && stristr($column->name, 'html')) {
            //$modelname = get_class($model);
            return "\$this->widget('CKEditor', array('model'=>\$model,'attribute'=>'{$column->name}','options'=>Yii::app()->params['ext.ckeditor.options']));";
        } else {
            return null;
        }
    }

}
?>

