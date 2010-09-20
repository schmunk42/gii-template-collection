<?php
/**
 * Cache Generator
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('CacheGenerator',dirname(__FILE__));
class CacheGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.cache.CacheCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['CacheCode'][$_GET['attribute']]);
    }
}
?>