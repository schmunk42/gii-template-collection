<?php
/**
 * Validator Generator.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

Yii::setPathOfAlias('ValidatorGenerator',dirname(__FILE__));
class ValidatorGenerator extends CCodeGenerator
{
    public $codeModel='ext.gtc.validator.ValidatorCode';

    public function actionPreview()
    {
        $parser=new CMarkdownParser;
        echo $parser->safeTransform($_POST['ValidatorCode'][$_GET['attribute']]);
    }
}
?>