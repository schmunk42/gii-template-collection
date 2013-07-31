<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class GtcActiveFieldProvider extends GtcCodeProvider
{
    public function generateActiveLabel($model, $column)
    {
        if ($column->autoIncrement) {
            return false;
        }
    }

    public function generateActiveField($model, $column)
    {
        if ($column->autoIncrement) {
            return false;
        }

        if (strtoupper($column->dbType) == 'TEXT' && stristr($column->name, 'html')) {
            return "\$this->widget('CKEditor', array('model'=>\$model,'attribute'=>'{$column->name}','options'=>Yii::app()->params['ext.ckeditor.options']));";
        } elseif (strtoupper($column->dbType) == 'TINYINT(1)'
            || strtoupper($column->dbType) == 'BIT'
            || strtoupper($column->dbType) == 'BOOL'
            || strtoupper($column->dbType) == 'BOOLEAN'
        ) {
            return "echo \$form->checkBox(\$model,'{$column->name}')";
        } elseif (strtoupper($column->dbType) == 'DATE') {
            return ("\$this->widget('zii.widgets.jui.CJuiDatePicker',
                         array(
                                 'model'=>\$model,
                                 'attribute'=>'{$column->name}',
                                 'language'=> substr(Yii::app()->language,0,strpos(Yii::app()->language,'_')),
                                 'htmlOptions'=>array('size'=>10),
                                 'options'=>array(
                                     'showButtonPanel'=>true,
                                     'changeYear'=>true,
                                     'changeYear'=>true,
                                     'dateFormat'=>'yy-mm-dd',
                                     ),
                                 )
                             );
                    ");
        } elseif (substr(strtoupper($column->dbType), 0, 4) == 'ENUM') {
            $string = sprintf("echo CHtml::activeDropDownList(\$model, '%s', array(\n", $column->name);

            $enum_values = explode(',', substr($column->dbType, 4, strlen($column->dbType) - 1));

            foreach ($enum_values as $value) {
                $value = trim($value, "()'");
                $string .= "            '$value' => '" . $value . "' ,\n";
            }
            $string .= '))';

            return $string;
        } else {
            return null;
        }
    }

}

?>
