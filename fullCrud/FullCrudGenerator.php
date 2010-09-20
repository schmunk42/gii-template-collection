<?php
class FullCrudGenerator extends CCodeGenerator
{
	public $codeModel='ext.gtc.fullCrud.FullCrudCode';


	/**
	 * Returns the model names in an array.
	 * Only non abstract and superclasses of CActiveRecord models are returned.
	 * The array is used to build the autocomplete field.
	 * @return array the names of the models
	 */
	protected function getModels() {
		$models = array();
		$files = scandir(Yii::getPathOfAlias('application.models'));
		foreach ($files as $file) {
			if (substr($file, 0, 1) !== '.'
					&& substr($file, 0, 4) !== 'Base'
					&& $file != 'GActiveRecord'
					&& strtolower(substr($file, -4)) === '.php') {
				$fileClassName = substr($file, 0, strpos($file, '.'));
				if (class_exists($fileClassName) && is_subclass_of($fileClassName, 'CActiveRecord')) {
					$fileClass = new ReflectionClass($fileClassName);
					if ($fileClass->isAbstract())
						continue;
					$models[] = $fileClassName;
				}
			}
		}
		return $models;
	}
}
?>
