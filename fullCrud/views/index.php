<?php
$class=get_class($model);
Yii::app()->clientScript->registerScript('gii.crud',"
$('#{$class}_controller').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_model').bind('keyup change', function(){
	var controller=$('#{$class}_controller');
	if(!controller.data('changed')) {
		var module=new String($(this).val().match(/^\\w*/));
		var id=new String($(this).val().match(/\\w*$/));
		if(id.length>0)
			id=id.substring(0,1).toLowerCase()+id.substring(1);
		if($(this).val().match(/\./) && !$(this).val().match(/application\./))
			id=module+'/'+id.substring(0,1).toLowerCase()+id.substring(1);
		controller.val(id);
	}
});
");
?>
<h1>Full Crud Generator</h1>

<p>This generator generates a controller and views that implement CRUD operations for the specified data model. </p>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>
<?php $this->widget('system.web.widgets.CTabView', array('tabs'=>array(
    'tab1'=>array(
	    'title'=>'CRUD Options', 'view'=>'crud',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
    'tab2'=>array(
	    'title'=>'Info', 'view'=>'info',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
)));
?>
<?php $this->endWidget(); ?>
