<?php

abstract class GActiveRecord extends CActiveRecord {
	public function behaviors()
	{
		return array_merge(parent::behaviors(), array(
					'CSaveRelationsBehavior' => array(
						'class' => 'ext.gtc.components.CSaveRelationsBehavior'
						),
					'TimestampBehavior' => array(
						'class' => 'ext.gtc.components.TimestampBehavior'
						)
					)
				);
	}

	public function  __toString() {
		return $this->id;
	}

	public function getRecordTitle(){
		$nameColumn = GHelper::guessNameColumn($this->tableSchema->columns);
		return $this->$nameColumn;
	}
}
