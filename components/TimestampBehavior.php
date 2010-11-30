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
	public $integer_timestamps = true;

	public function beforeValidate($on) {
	if(isset($this->Owner->tableSchema->columns[$this->createtime]))
		if ($this->Owner->isNewRecord)
			if($this->integer_timestamps)
				$this->Owner->{$this->createtime} = time();
			else
				$this->Owner->{$this->createtime} = new CDbExpression('NOW()');

	if(isset($this->Owner->tableSchema->columns[$this->updatetime]))
			if($this->integer_timestamps)
				$this->Owner->{$this->updatetime} = time();
			else
				$this->Owner->{$this->updatetime} = new CDbExpression('NOW()');

		return true;
	}
}

