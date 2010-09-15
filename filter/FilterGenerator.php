<?php
Yii::setPathOfAlias('FilterGenerator',dirname(__FILE__));
class FilterGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.filter.FilterCode';
}
?>
