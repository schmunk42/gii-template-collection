<?php
/**
 * Behavior Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('BehaviorGenerator',dirname(__FILE__));
class BehaviorGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.behavior.BehaviorCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['BehaviorCode'][$_GET['attribute']]);
    }
}
?>