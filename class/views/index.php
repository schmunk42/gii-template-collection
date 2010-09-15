<h1>Class Generator</h1>
<p>This generator helps you to quickly generate the skeleton code for a any PHP class.</p>
<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>
<?php $this->widget('system.web.widgets.CTabView', array('tabs'=>array(
    'tab1'=>array(
	    'title'=>'Class', 'view'=>'__class',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
    'tab2'=>array(
	    'title'=>'Property Definitions', 'view'=>'__properties',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
    'tab3'=>array(
	    'title'=>'Methods', 'view'=>'__methods',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
    'tab4'=>array(
	    'title'=>'Comment', 'view'=>'__comment',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
    'tab5'=>array(
	    'title'=>'Info', 'view'=>'__infos',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
)));
?>
<?php $this->endWidget(); ?>