<?php
Yii::setPathOfAlias('ValidatorGenerator',dirname(__FILE__));
class ValidatorGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.validator.ValidatorCode';
}
?>
