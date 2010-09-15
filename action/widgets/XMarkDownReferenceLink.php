<?php
/**
 * XMarkDownReferenceLink class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @link http://www.yiiframework.com/extension/yii-class-generator-suite/
 * @license BSD
 */

/**
 * XMarkDownReferenceLink is a widget to generate links to
 * documentations for the MakDown Sytax
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

class XMarkDownReferenceLink extends CWidget
{
    public $codeModel;

	/**
	 * Executes the widget.
	 */
    public function run()
    {
        echo $this->renderMarkDownLink();
    }

    private function renderMarkDownLink()
    {
        $currentLang = Yii::app()->language;
        $country = explode("_",$currentLang);
        $link = '<p class="hint">';
        switch($country[0]){
            case 'de':
                $link .= 'You may use <a target="_blank" href="http://www.markdown.de">Markdown syntax</a>.';
                break;
            case 'ru':
                $link .= 'You may use <a target="_blank" href="http://ru.wikipedia.org/wiki/Markdown">Markdown syntax</a>.';
                break;
            case 'es':
                $link .= 'You may use <a target="_blank" href="http://es.wikipedia.org/wiki/Markdown">Markdown syntax</a>.';
                break;
            case 'fr':
                $link .= 'You may use <a target="_blank" href="http://fr.wikipedia.org/wiki/Markdown">Markdown syntax</a>.';
                break;
            case 'ja':
                $link .= 'You may use <a target="_blank" href="http://ja.wikipedia.org/wiki/Markdown">Markdown syntax</a>.';
                break;
            case 'ko':
                $link .= 'You may use <a target="_blank" href="http://ko.wikipedia.org/wiki/Markdown">Markdown syntax</a>.';
                break;
            case 'pl':
                $link .= 'You may use <a target="_blank" href="http://pl.wikipedia.org/wiki/Markdown">Markdown syntax</a>.';
                break;
            case 'pt':
                $link .= 'You may use <a target="_blank" href="http://pt.wikipedia.org/wiki/Markdown">Markdown syntax</a>.';
                break;
            default:
                $link .= 'You may use <a target="_blank" href="http://daringfireball.net/projects/markdown/syntax">Markdown syntax</a>.';
        }
        return $link . '</p>';
    }
}