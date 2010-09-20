<?php
/**
 * Event Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('EventGenerator',dirname(__FILE__));
class EventGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.event.EventCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['EventCode'][$_GET['attribute']]);
    }
}
?>