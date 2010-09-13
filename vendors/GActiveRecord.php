<?php

abstract class GActiveRecord extends CActiveRecord {

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
