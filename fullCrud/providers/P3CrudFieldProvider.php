<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class P3CrudFieldProvider{

	static public function generateActiveField($model, $column) {

		if (strtoupper($column->dbType) == 'TEXT' && strstr($column->name, 'html')) {
			$modelname = get_class($model);
			return ("\$this->widget('p3widgets.extensions.ckeditor.CKEditor', array('model'=>\$model,'attribute'=>'{$column->name}','options'=>Yii::app()->params['ext.ckeditor.options']));");
		} else {
			return null;
		}
	}

}

?>

