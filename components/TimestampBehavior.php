<?php
// Timestamp behavior for timestamp fields.
// Thanks to http://www.yiiframework.com/wiki/14/autotimestampbehavior !

class TimestampBehavior extends CActiveRecordBehavior {
	/**
	 * The field that stores the creation time
	 */
	public $createtime = 'createtime';
	/**
	 * The field that stores the modification time
	 */
	public $updatetime = 'updatetime';


	public function beforeValidate($on) {
		if ($this->Owner->isNewRecord)
			$this->Owner->{$this->createtime} = new CDbExpression('NOW()');
		else
			$this->Owner->{$this->updatetime} = new CDbExpression('NOW()');

		return true;
	}
}

