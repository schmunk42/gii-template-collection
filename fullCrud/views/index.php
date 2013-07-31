<?php
$class = get_class($model);
Yii::app()->clientScript->registerScript(
    'gii.crud',
    "
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
"
);
?>
<h1>Full Crud Generator</h1>

<p>This generator generates a controller and views that implement CRUD operations for the specified data model. </p>


<?php $form = $this->beginWidget('CCodeForm', array('model' => $model)); ?>

<p> <?php
    echo CHtml::link(
        'Click here to see what FullCrud does exactly',
        '#',
        array(
             'onClick' => '$(".details").toggle()'
        )
    );
    ?> </p>

<div class="details" style="display: none;">
    <?php
    $this->renderPartial(
        'info',
        array(
             'model' => $model,
             'form'  => $form
        )
    );
    ?>
</div>

<?php
$this->renderPartial(
    'crud',
    array(
         'model' => $model,
         'form'  => $form
    )
);

if (isset($_POST['preview']) && !$model->hasErrors()) {
    $this->renderPartial(
        'url_hint',
        array(
             'model' => $model,
             'form'  => $form
        )
    );
}

if (isset($_POST['preview']) && !$model->hasErrors()) {

    switch ($this->_widgetStack[0]->model->template) {
        case "hybrid":

            $this->renderPartial('hybrid_template_deps');

            break;
    }

}

$this->endWidget();
?>
