<?php $models = $this->getModels(); ?>
<div class="row">
    <?php echo $form->labelEx($model, 'model'); ?>
    <?php
    $form->widget(
        'zii.widgets.jui.CJuiAutoComplete',
        array(
             'model'       => $model,
             'attribute'   => 'model',
             'source'      => array_keys($models),
             'options'     => array(
                 'delay' => 100,
                 'focus' => 'js:function(event,ui){
                $(this).val($(ui.item).val());
                $(this).trigger(\'change\');
                }',
             ),
             'htmlOptions' => array(
                 'size' => '65',
             ),
        )
    );
    ?>
    <div class="tooltip">
        Model class is case-sensitive. It can be either a class name (e.g. <code>Post</code>)
        or the path alias of the class file (e.g. <code>application.models.Post</code>).
        Note that if the former, the class must be auto-loadable.
    </div>
    <?php echo $form->error($model, 'model'); ?>
</div>

<div class="row sticky">
    <?php echo $form->labelEx($model, 'baseControllerClass'); ?>
    <?php echo $form->textField($model, 'baseControllerClass', array('size' => 65)); ?>
    <div class="tooltip">
        This is the class that the new CRUD controller class will extend from.
        Please make sure the class exists and can be autoloaded.
    </div>
    <?php echo $form->error($model, 'baseControllerClass'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'controller'); ?>
    <?php echo $form->textField($model, 'controller', array('size' => 65)); ?>
    <div class="tooltip">
        Controller ID is case-sensitive. CRUD controllers are often named after
        the model class name that they are dealing with. Below are some examples:
        <ul>
            <li><code>post</code> generates <code>PostController.php</code></li>
            <li><code>postTag</code> generates <code>PostTagController.php</code></li>
            <li><code>admin/user</code> generates <code>admin/UserController.php</code></li>
        </ul>
    </div>
    <?php echo $form->error($model, 'controller'); ?>
</div>

<div class="row wide">
    <?php echo $form->labelEx($model, 'messageCatalogStandard'); ?>
    <?php echo $form->textField(
        $model,
        'messageCatalogStandard',
        array(
             'size' => 65
        )
    );
    ?>
    <div class="tooltip">
        Message catalog for CRUD standard hints and labels.
    </div>
    <?php echo $form->error($model, 'messageCatalogStandard'); ?>
</div>

<div class="row wide">
    <?php echo $form->labelEx($model, 'messageCatalog'); ?>
    <?php echo $form->textField(
        $model,
        'messageCatalog',
        array(
             'size' => 65
        )
    );
    ?>
    <div class="tooltip">
        Message catalog for CRUD generated hints and labels.
    </div>
    <?php echo $form->error($model, 'messageCatalog'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'validation'); ?>
    <?php
    echo $form->dropDownList(
        $model,
        'validation',
        array(
             3 => 'Enable Ajax and Client-side Validation',
             2 => 'Enable Client Validation',
             1 => 'Enable Ajax Validation',
             0 => 'Disable ajax Validation'
        )
    );
    ?>
    <div class="tooltip">
        Enables instant Validation of input fields via Yii's form Generator and ajax
        requests after blur() on the field. Since Yii 1.1.7 you can also enable
        client-side validation for most of the validation rules
    </div>
    <?php echo $form->error($model, 'validation'); ?>
</div>

<h3>Slim Editable Code Template specific</h3>
<fieldset>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'icon');
        echo $form->textField($model,'icon',array('size' => 30));
        ?>
        <div class="tooltip">
            Common icon in header for admin, create, view
        </div>
        <?php echo $form->error($model, 'icon'); ?>
    </div>
    
</fieldset>
<h3>Slim Code Template specific</h3>

<fieldset>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'authTemplateSlim');
        echo $form->dropDownList($model, 'authTemplateSlim', $this->getAuthTemplates('slim'));
        ?>
        <div class="tooltip">
            The Authentication method to be used in the Controller. Yii access Control is the
            default accessControl of Yii using the Controller accessRules() method. No access
            Control provides no Access control.
        </div>
        <?php echo $form->error($model, 'authTemplateSlim'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'formEnctype');
        echo $form->textField($model, 'formEnctype');
        ?>
        <div class="tooltip">
            E.g. 'multipart/form-data'
        </div>
        <?php echo $form->error($model, 'formEnctype'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'formLayout');
        echo $form->dropDownList(
            $model,
            'formLayout',
            array(
                 'span12-span12' => 'One Column',
                 'span7-span5' => 'Two Columns (span7, span5)',
                 'span5-span7' => 'Two Columns (span5, span7)',
            )
        );   ?>
        <div class="tooltip">
            How Data and Relations should be layouted.
        </div>
        <?php echo $form->error($model, 'formEnctype'); ?>
    </div>

</fieldset>

<h3>Hybrid Code Template specific</h3>

<fieldset>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'authTemplateHybrid');
        echo $form->dropDownList($model, 'authTemplateHybrid', $this->getAuthTemplates('hybrid'));
        ?>
        <div class="tooltip">
            The Authentication method to be used in the Controller. Yii access Control is the
            default accessControl of Yii using the Controller accessRules() method. No access
            Control provides no Access control.
        </div>
        <?php echo $form->error($model, 'authTemplateHybrid'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'formOrientation'); ?>
        <?php
        echo $form->dropDownList(
            $model,
            'formOrientation',
            array(
                 'horizontal' => 'Horizontal',
                 'vertical'   => 'Vertical',
            )
        );
        ?>
        <div class="tooltip">
            Valid for "Hybrid" template only. Determines the "type" attribute for the update forms.
            See <?php CHtml::link(
                'http://yiibooster.clevertech.biz/components.html#forms'
            ); ?> for an example.
        </div>
        <?php echo $form->error($model, 'formOrientation'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'textEditor'); ?>
        <?php
        echo $form->dropDownList(
            $model,
            'textEditor',
            array(
                 'textarea'       => 'Plain Text Area',
                 'redactor'       => 'Redactor WYSIWYG',
                 'html5Editor'    => 'Bootstrap WYSIHTML5',
                 'ckEditor'       => 'CKEditor WYSIWYG',
                 'markdownEditor' => 'Markdown Editor',
            )
        );
        ?>
        <div class="tooltip">
            Valid for "Hybrid" template only. Determines the type of field used for TEXT-type fields.
            See <?php CHtml::link(
                'http://yiibooster.clevertech.biz/components.html#forms'
            ); ?> for a demo of the different editors.
        </div>
        <?php echo $form->error($model, 'textEditor'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'internalModels'); ?>
        <?php
        echo $form->listBox(
            $model,
            'internalModels',
            array_combine($this->getModelClasses(), $this->getModelClasses()),
            array('multiple' => true, 'size' => '20')
        );
        ?>
        <div class="tooltip">
            Internal models are not meant to be handled with-in a web ui and will not have modal forms generated for
            them.
        </div>
        <?php echo $form->error($model, 'textEditor'); ?>
    </div>

    <div class="row sticky">
        <?php echo $form->labelEx($model, 'backendViewPathAlias'); ?>
        <?php echo $form->textField($model, 'backendViewPathAlias', array('size' => 65)); ?>
        <div class="tooltip">
            This refers to the directory that the backend views should be generated under.
            It should be specified in the form of a path alias, for example,
            <code>application.themes.backend.views</code>.
        </div>
        <?php echo $form->error($model, 'backendViewPathAlias'); ?>
    </div>

    <div class="row sticky">
        <?php echo $form->labelEx($model, 'frontendViewPathAlias'); ?>
        <?php echo $form->textField($model, 'frontendViewPathAlias', array('size' => 65)); ?>
        <div class="tooltip">
            This refers to the directory that the frontend views should be generated under.
            It should be specified in the form of a path alias, for example,
            <code>application.themes.frontend.views</code>.
        </div>
        <?php echo $form->error($model, 'frontendViewPathAlias'); ?>
    </div>

</fieldset>

<h3>Legacy Code Template specific</h3>

<fieldset>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'authTemplate');
        echo $form->dropDownList($model, 'authTemplate', $this->getAuthTemplates('legacy'));
        ?>
        <div class="tooltip">
            The Authentication method to be used in the Controller. Yii access Control is the
            default accessControl of Yii using the Controller accessRules() method. No access
            Control provides no Access control.
        </div>
        <?php echo $form->error($model, 'authTemplate'); ?>
    </div>

</fieldset>

<style>
    input.radio {
        display: inline !important;
    }
</style>
