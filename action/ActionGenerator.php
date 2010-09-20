<?php
/**
 * Action Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('ActionGenerator',dirname(__FILE__));
class ActionGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.action.ActionCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['ActionCode'][$_GET['attribute']]);
    }
}
?>