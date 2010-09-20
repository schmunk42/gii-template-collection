<?php
/**
 * Action Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('CommandGenerator',dirname(__FILE__));
class CommandGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.command.CommandCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['CommandCode'][$_GET['attribute']]);
    }
}
?>