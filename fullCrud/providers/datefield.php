$this->widget('zii.widgets.jui.CJuiDatePicker',
						 array(
								 'model'=>'{$model}',
								 'name'=>'{$modelname}[{$column}]',
								 'language'=> Yii::app()->language,
								 'value'=>{$model}->{$column},
								 'htmlOptions'=>array(
									 'size'=>10,
									 'style'=>'width:80px !important'),
								 'options'=>array(
									 'showButtonPanel'=>true,
									 'changeYear'=>true,
									 'changeYear'=>true,
									 'dateFormat'=>'yy-mm-dd',
									 ),
								 )
							 );
					");

