<?php
Yii::setPathOfAlias('PortletGenerator',dirname(__FILE__));
class PortletGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.portlet.PortletCode';
}
?>
