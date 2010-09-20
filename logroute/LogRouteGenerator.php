<?php
/**
 * Log Route Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('LogRouteGenerator',dirname(__FILE__));
class LogRouteGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.logroute.LogRouteCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['LogRouteCode'][$_GET['attribute']]);
    }
}
?>