<?php

class GtcActiveRecord extends CActiveRecord {

	public function behaviors()
	{
		return array_merge(parent::behaviors(), array(
					'CSaveRelationsBehavior' => array(
						'class' => 'ext.CSaveRelationsBehavior'
						)
					)
				);
	}
}
