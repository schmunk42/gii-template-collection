<?php

class FullCrudGenerator extends CCodeGenerator {

	public $codeModel = 'ext.gtc.fullCrud.FullCrudCode';

	/**
	 * Returns the model names in an array.
	 * Only non abstract and superclasses of CActiveRecord models are returned.
	 * The array is used to build the autocomplete field.
	 * @return array the names of the models
	 */
	protected function getModels() {
		$models = array();
		$aliases = array();
		$aliases[] = 'application.models';
		foreach (Yii::app()->getModules() as $moduleName => $config) {
			$aliases[] = $moduleName . ".models";
		}

		foreach ($aliases as $alias) {
			$files = scandir(Yii::getPathOfAlias($alias));
			Yii::import($alias.".*");
			foreach ($files as $file) {
				if ($fileClassName = $this->checkFile($file, $alias))
					$models[] = $alias.".".$fileClassName;
			}
		}

		return $models;
	}

	private function checkFile($file, $alias = '') {
		if (substr($file, 0, 1) !== '.'
			&& substr($file, 0, 4) !== 'Base'
			&& $file != 'GActiveRecord'
			&& strtolower(substr($file, -4)) === '.php') {
			$fileClassName = substr($file, 0, strpos($file, '.'));
			if (class_exists($fileClassName) && is_subclass_of($fileClassName, 'CActiveRecord')) {
				$fileClass = new ReflectionClass($fileClassName);
				if ($fileClass->isAbstract())
					return null;
				else
					return $models[] = $fileClassName;
			}
		}
	}

}

?>
