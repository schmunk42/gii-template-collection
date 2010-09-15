<?php
class ClassCode extends CCodeModel
{
    public $className;
    public $baseClass='CComponent';
    public $scriptPath='application.components';
    public $isAbstract=false;
    public $interfaceName;
    public $comment;
    public $md_comment; // comment after mardown parse

    public $propertyName;
    public $commentProperty;
    public $propertyGetter;
    public $propertySetter;
    public $propertyScope;
    public $subject2;

    public $codeBody;
    public $magicMethods;

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('className, scriptPath', 'required'),
            array('className', 'match', 'pattern'=>'/^\w+$/'),
			array('baseClass', 'match', 'pattern'=>'/^\w+$/', 'message'=>'{attribute} should only contain word characters.'),
			array('baseClass', 'sticky'),
            array('scriptPath', 'validateScriptPath'),
            array('scriptPath', 'sticky'),
            array('isAbstract', 'boolean'),
            array('isAbstract', 'boolean'),
            array('comment,interfaceName,magicMethods,codeBody', 'safe'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'className'=>'Class',
            'baseClass'=>'extends',
            'interfaceName'=>'implements',
            'comment'=>'class-level DocBlock for your component',
            'scriptPath'=>'Script Path',
            'isAbstract'=>'The class is abstract.',
            'codeBody'=>'Your codelines after the magic methods',
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
        $path=Yii::getPathOfAlias($this->scriptPath).'/' . ucfirst($this->className) . '.php';
        $code=$this->render($this->templatepath.'/php5class.php');
        $this->files[]=new CCodeFile($path, $code);
    }

    public function getPhpInfo()
    {
        ob_start();
        phpinfo();
        $info = ob_get_contents();
        ob_end_clean();
        return preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);
    }

	public function renderSelection($field,$style,$data,$htmlOptions=array()) {
		if(strcasecmp($style, "selectbox") == 0)
			echo CHtml::ActiveDropDownList(
            $this,
			$field,
			$data,
			$this);
		else if(strcasecmp($style, "listbox") == 0)
			echo CHtml::ActiveListBox(
            $this,
			$field,
			$data,
			$htmlOptions);
		else if(strcasecmp($style, "checkbox") == 0)
			echo CHtml::ActiveCheckBoxList(
            $this,
			$field,
			$data,
			$htmlOptions);
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

    /**
     * Logs a message.
     *
     * @param string $message Message to be logged
     * @param string $level Level of the message (e.g. 'trace', 'warning',
     * 'error', 'info', see CLogger constants definitions)
     */
    protected static function log($message, $level='error')
    {
        Yii::log($message, $level, __CLASS__);
    }

    /**
     * Dumps a variable or the object itself in terms of a string.
     *
     * @param mixed variable to be dumped
     */
    protected static function dump($var='dump-the-object',$highlight=true)
    {
        if ($var === 'dump-the-object') {
            return CVarDumper::dumpAsString($this,$depth=15,$highlight);
        } else {
            return CVarDumper::dumpAsString($var,$depth=15,$highlight);
        }
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

}
?>