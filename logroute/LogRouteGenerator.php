<?php
Yii::setPathOfAlias('LogRouteGenerator',dirname(__FILE__));
class LogRouteGenerator extends CCodeGenerator
{
    public $codeModel='application.gii.logroute.LogRouteCode';
}
?>