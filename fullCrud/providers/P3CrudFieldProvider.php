<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FullCrudFieldProvider{

	static public function generateActiveField($model, $column) {

		if (strtoupper($column->dbType) == 'TEXT') {
			$modelname = get_class($model);
			return ("\$this->widget('p3widgets.extensions.ckeditor.CKEditor', array('model'=>$modelname,'attribute'=>'{$column->name}','options'=>Yii::app()->params['ext.ckeditor.options']));");
		} else {
			return null;
		}
	}

}

?>

