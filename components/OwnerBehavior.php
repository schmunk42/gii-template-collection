<?php
// Owner Behavior by thyseus <thyseus@gmail.com>

// Assuming a dataset is "owned" by a user, we need to set the id
// of the current logged in user when saving the dataset automatically.
// Optional, a "last updated by" column can save the user that last updated
// the dataset. The Owner will never be touched.


class OwnerBehavior extends CActiveRecordBehavior {
	/**
	 * The field that stores the pk of the owner
	 */
	public $ownerColumn = 'owner_id';

	/**
	 * The field that stores the pk of user that did the the last change
	 */
	public $lastChangeColumn = 'last_change_by';

	public function beforeValidate($on) {
		if(isset($this->owner->tableSchema->columns[$this->ownerColumn]))
			if ($this->owner->isNewRecord)
				$this->owner->{$this->ownerColumn} = Yii::app()->user->id;


		return true;
	}

	public function beforeSave($on) {

		if(isset($this->owner->tableSchema->columns[$this->lastChangeColumn]))
			$this->owner->{$this->lastChangeColumn} = Yii::app()->user->id;

		return true;
	}

}

