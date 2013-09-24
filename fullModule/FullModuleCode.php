<?php
Yii::import('system.gii.generators.module.ModuleCode');

class FullModuleCode extends ModuleCode
{



    public function prepare()
    {
        parent::prepare();
        var_dump($this->files);exit;
		$templatePath=$this->templatePath;
		$modulePath=$this->modulePath;
		$moduleTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'translation.php';

		$this->files[]=new CCodeFile(
			$modulePath.'/'.$this->moduleClass.'.php',
			$this->render($moduleTemplateFile)
		);     
        

    }

}

?>
