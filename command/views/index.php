<?php Yii::import('CommandGenerator.widgets.ddeditor.*'); ?>
<h1>Console Command Generator</h1>
<p>This generator helps you to quickly generate the skeleton code for a new console command class.</p>
<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>
<?php $this->widget('system.web.widgets.CTabView', array('tabs'=>array(
    'tab1'=>array(
	    'title'=>'Component', 'view'=>'__class',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
    'tab2'=>array(
	    'title'=>'Comment', 'view'=>'__comment',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
    'tab3'=>array(
	    'title'=>'Info', 'view'=>'__infos',
        'data'=>array('model'=>$model,'form'=>$form),
	 ),
)));
?>
<?php $this->endWidget(); ?>