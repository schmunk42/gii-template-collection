<?php
Yii::setPathOfAlias('EventGenerator',dirname(__FILE__));
class EventGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.event.EventCode';
}
?>
