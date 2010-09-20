<?php
class VRCode extends CCodeModel
{
    public $className;
    public $baseClass='CViewRenderer';
    public $scriptPath='application.web.renderers';
    public $comment;
    public $md_comment; // comment after mardown parse

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('className,baseClass,scriptPath', 'required'),
            array('className', 'match', 'pattern'=>'/^\w+$/'),
			array('baseClass', 'match', 'pattern'=>'/^\w+$/', 'message'=>'{attribute} should only contain word characters.'),
			array('baseClass', 'sticky'),
            array('scriptPath', 'validateScriptPath'),
            array('scriptPath', 'sticky'),
            array('comment', 'safe'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'baseClass'=>'Base Class',
            'className'=>'View Renderer Class Name',
            'scriptPath'=>'Script Path',
            'comment'=>'class-level DocBlock for your component',
        ));
    }

	public function validateScriptPath($attribute,$params)
	{
		if($this->hasErrors('scriptPath'))
			return;
		if(Yii::getPathOfAlias($this->scriptPath)===false)
			$this->addError('scriptPath','Script Path must be a valid path alias.');
	}

    public function prepare()
    {
        $path=Yii::getPathOfAlias($this->scriptPath).'/' . ucfirst($this->className) . 'ViewRenderer.php';
        $code=$this->render($this->templatepath.'/vr.php');
        $this->files[]=new CCodeFile($path, $code);
    }

	/**
	 * Prepares comment before performing validation.
	 */
	protected function beforeValidate()
	{
        $parser= new CMarkdownParser;
		$this->md_comment = $parser->transform($this->comment);
		return true;
	}

    public function startComment()
    {

        return "/**\n";
    }

    public function endComment()
    {
        return " */\n";
    }

    public function renderComment()
    {
        if (!$this->comment) return '';
        $result  = $this->startComment();
        $result .= $this->renderCommentPart($this->md_comment);
        return $result . $this->endComment();
    }

    public function renderCommentPart($comment = false)
    {
        if ($comment===false)
            $comment = $this->md_comment;
        $lines = explode("\n", $comment);
        $part = '';
        foreach($lines as $line){
            $part .= $this->renderCommentLine($line);
        }
        return $part;
    }

    public function renderCommentLine($line = '', $newLine = true)
    {
        if (!$line) return '';
        return ($newLine) ? " * " . $line . "\n"
                          : " * " . $line;
    }

	public function successMessage()
	{
		$output=<<<EOD
<p>The View Renderer has been generated successfully.</p>
EOD;
		$code=$this->render($this->templatePath.'/vr.php');
		return $output.highlight_string($code,true);
	}

    public function p($name)
    {
        return Yii::app()->params[$name];
    }


}
?>