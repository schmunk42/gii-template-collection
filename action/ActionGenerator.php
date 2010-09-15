<?php
Yii::setPathOfAlias('ActionGenerator',dirname(__FILE__));
class ActionGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.action.ActionCode';
}
?>
