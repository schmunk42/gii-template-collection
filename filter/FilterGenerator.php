<?php
/**
 * Filter Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('FilterGenerator',dirname(__FILE__));
class FilterGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.filter.FilterCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['FilterCode'][$_GET['attribute']]);
    }
}
?>