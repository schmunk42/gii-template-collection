<?php
$class = get_class($model);
Yii::app()->clientScript->registerScript('gii.model', "
$('#{$class}_modelClass').change(function(){
    $(this).data('changed',$(this).val()!='');
});
$('#{$class}_tableName').bind('keyup change', function(){
    var model=$('#{$class}_modelClass');
    var tableName=$(this).val();
    if(tableName.substring(tableName.length-1)!='*') {
        $('.form .row.model-class').show();
    }
    else {
        $('#{$class}_modelClass').val('');
        $('.form .row.model-class').hide();
    }
    if(!model.data('changed')) {
        var i=tableName.lastIndexOf('.');
        if(i>=0)
            tableName=tableName.substring(i+1);
        var tablePrefix=$('#{$class}_tablePrefix').val();
        if(tablePrefix!='' && tableName.indexOf(tablePrefix)==0)
            tableName=tableName.substring(tablePrefix.length);
        var modelClass='';
        $.each(tableName.split('_'), function() {
            if(this.length>0)
                modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
        });
        model.val(modelClass);
    }
});
$('.form .row.model-class').toggle($('#{$class}_tableName').val().substring($('#{$class}_tableName').val().length-1)!='*');
");
?>
<h1>Full Model Generator</h1>

<p>This generator generates an enhanced model class for the specified database table. </p>

<p> <?php echo CHtml::link(
    'Click here to see what FullModel does exactly', '#', array(
                                                               'onClick' => '$(".details").toggle()')); ?> </p>

<div class="details" style="display: none;">
    <ul>
        <li> At first, the model is split into two files: a BaseModel.php file
            and a Model.php file extending from the BaseModel. This makes it possible
            to override the BaseModel when the database schema changes, without
            overriding any business logic that should go into the Model.php file.
            To disable this behavior, choose the 'singlefile' template instead of the
            default.
        </li>
        <li> It will include the GtcSaveRelationsBehavior to add additional functions for MANY_MANY relations</li>
        <li> It adds the __toString() php magic function to the Base Model class</li>
        <li> All comments are removed except for the first comment describing the attributes of the model</li>
        <li> Attribute labels gets a Yii::t('app', 'label') wrapper to make easier i18n possible</li>
        <li> If a column is called <em> create_time, createtime, update_time, updatetime or timestamp </em> the
            CTimestampBehavior shipped with zii will automatically added as a behavior
        </li>
        <li> If a column is called <em> created_by, createdby, create_user, user, user_id, owner or owner_id </em> the
            OwnerBehavior shipped with the gii-template-collection will automatically added as a behavior
        </li>

    </ul>

</div>

<?php
$form = $this->beginWidget('CCodeForm', array('model' => $model));
?>

<div class="row sticky">
    <?php echo $form->labelEx($model, 'tablePrefix'); ?>
    <?php echo $form->textField($model, 'tablePrefix', array('size' => 65)); ?>
    <div class="tooltip">
        This refers to the prefix name that is shared by all database tables.
        Setting this property mainly affects how model classes are named based on
        the table names. For example, a table prefix <code>tbl_</code> with a table name <code>tbl_post</code>
        will generate a model class named <code>Post</code>.
        <br/>
        Leave this field empty if your database tables do not use common prefix.
    </div>
    <?php echo $form->error($model, 'tablePrefix'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model, 'tableName'); ?>
    <?php $form->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                                                 'model' => $model,
                                                                 'attribute' => 'tableName',
                                                                 'source' => $this->getTables($model->connectionId),
                                                                 'options' => array(
                                                                     'delay' => 100,
                                                                     'focus' => 'js:function(event,ui){
                    $(this).val($(ui.item).val());
                    $(this).trigger(\'change\');
                }',
                                                                 ),
                                                                 'htmlOptions' => array(
                                                                     'size' => '65',
                                                                 ),
                                                            ));
    ?>
    <div class="tooltip">
        This refers to the table name that a new model class should be generated for
        (e.g. <code>tbl_user</code>). It can contain schema name, if needed (e.g. <code>public.tbl_post</code>).
        You may also enter <code>*</code> (or <code>schemaName.*</code> for a particular DB schema)
        to generate a model class for EVERY table.
    </div>
    <?php echo $form->error($model, 'tableName'); ?>
</div>
<div class="row model-class">
    <?php echo $form->label($model, 'modelClass', array('required' => true)); ?>
    <?php echo $form->textField($model, 'modelClass', array('size' => 65)); ?>
    <div class="tooltip">
        This is the name of the model class to be generated (e.g. <code>Post</code>, <code>Comment</code>).
        It is case-sensitive.
    </div>
    <?php echo $form->error($model, 'modelClass'); ?>
</div>

<div class="row wide">
    <?php echo $form->labelEx($model, 'messageCatalog'); ?>
    <?php echo $form->textField($model, 'messageCatalog', array(
                                                               'size' => 65));
    ?>
    <div class="tooltip">
        Message catalog for CRUD hints and labels.
    </div>
    <?php echo $form->error($model, 'messageCatalog'); ?>
</div>

<div class="row sticky">
    <?php echo $form->labelEx($model, 'baseClass'); ?>
    <?php echo $form->textField($model, 'baseClass', array('size' => 65)); ?>
    <div class="tooltip">
        This is the class that the new model class will extend from.
        Please make sure the class exists and can be autoloaded.
    </div>
    <?php echo $form->error($model, 'baseClass'); ?>
</div>
<div class="row sticky">
    <?php echo $form->labelEx($model, 'modelPath'); ?>
    <?php echo $form->textField($model, 'modelPath', array('size' => 65)); ?>
    <div class="tooltip">
        This refers to the directory that the new model class file should be generated under.
        It should be specified in the form of a path alias, for example, <code>application.models</code>.
    </div>
    <?php echo $form->error($model, 'modelPath'); ?>
</div>
<div class="row sticky">
    <?php echo $form->labelEx($model, 'connectionId'); ?>
    <?php echo $form->textField($model, 'connectionId', array('size' => 65)); ?>
    <div class="tooltip">
        This refers to the database application component to use.
    </div>
    <?php echo $form->error($model, 'modelPath'); ?>
</div>



<?php $this->endWidget(); ?>
