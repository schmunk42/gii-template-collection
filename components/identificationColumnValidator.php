<?php
/**
 * IdentificationColumnValidator class file.
 *
 * @author Herbert Maschke <thyseus@gmail.com>
 * This file is part of the gii-template-collection
 */

/**
 * IdentificationColumnValidator is automatically applied by FullModel
 * to any column that 'identifies' the Model in a human-readable way. There
 * are certain rules that need to be applied, since the column can appear
 * in the URL. There should not be a / inside it and there should not be a .
 * (dot) as last character.
 *
 * Child classes must implement the {@link validateAttribute} method.
 *
 */
class IdentificationColumnValidator extends CValidator 
{
	protected function validateAttribute($object,$attribute) {
		if(isset($object->$attribute)) {
			if($object->$attribute) {
				if(substr($object->$attribute, 0, -1) == '.')
					$object->addError($attribute, Yii::t('app',
								'Please do not use a . (dot) as the last character for this column')); 

				if(strpos($object->$attribute, '/') !== false)
					$object->addError($attribute, Yii::t('app',
								'Please do not use a / (slash) for this column'));
			}
		}
	}

	public function clientValidateAttribute($object,$attribute)
	{
	}

}

