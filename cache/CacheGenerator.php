<?php
Yii::setPathOfAlias('CacheGenerator',dirname(__FILE__));
class CacheGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.cache.CacheCode';
}
?>
