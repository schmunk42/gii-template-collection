<?php
/**
 * Action Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('PortletGenerator',dirname(__FILE__));
class PortletGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.portlet.PortletCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['PortletCode'][$_GET['attribute']]);
    }
}
?>