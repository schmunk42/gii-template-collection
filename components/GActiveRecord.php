<?php

abstract class GActiveRecord extends CActiveRecord {

	public function behaviors()
	{
		return array_merge(parent::behaviors(), array(
					'CSaveRelationsBehavior' => array(
						'class' => 'CSaveRelationsBehavior'
						)
					)
				);
	}

	public function  __toString() {
		return $this->id;
	}
}
