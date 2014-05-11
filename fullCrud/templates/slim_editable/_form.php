<div class="crud-form">
    <?= "<?php {$this->provider()->generateHtml($this->modelClass, null, 'form-begin')} ?>" ?>
    <?=
    "
    <?php
        Yii::app()->bootstrap->registerPackage('select2');
        Yii::app()->clientScript->registerScript('crud/variant/update','$(\"#{$this->class2id($this->modelClass)}-form select\").select2();');


        \$form=\$this->beginWidget('TbActiveForm', array(
            'id' => '{$this->class2id($this->modelClass)}-form',
            'enableAjaxValidation' => {$this->enableAjaxValidation},
            'enableClientValidation' => {$this->enableClientValidation},
            'htmlOptions' => array(
                'enctype' => '{$this->formEnctype}'
            )
        ));

        echo \$form->errorSummary(\$model);
    ?>
    ";
    list($left_span,$right_span) = explode('-',$this->formLayout)                
    ?>

    <div class="row">
        <div class="<?= $left_span ?>">
            <div class="form-horizontal">

                <?php foreach ($this->tableSchema->columns as $column): ?>
                    <?php
                    // skip column with provider function
                    if ($this->provider()->skipColumn($this->modelClass, $column)) {
                        continue;
                    }
                    ?>

                    <?= "<?php {$this->provider()->generateHtml($this->modelClass, $column, 'prepend')} ?>" ?>

                    <div class="control-group">
                        <div class='control-label'>
                            <?= "<?php {$this->provider()->generateActiveLabel($this->modelClass, $column)} ?>" ?>

                        </div>
                        <div class='controls'>
                            <span class="tooltip-wrapper" data-toggle='tooltip' data-placement="right"
                                 title='<?= "<?php echo ((\$t = Yii::t('{$this->messageCatalog}', 'tooltip.{$column->name}')) != 'tooltip.{$column->name }')?\$t:'' ?>" ?>'>
                                <?=
                                "<?php
                            {$this->provider()->generateActiveField($this->modelClass, $column)};
                            echo \$form->error(\$model,'{$column->name}')
                            ?>"
                                ?>
                            </span>
                        </div>
                    </div>
                    <?= "<?php {$this->provider()->generateHtml($this->modelClass, $column, 'append')} ?>" ?>

                <?php endforeach; ?>

            </div>
        </div>
        <!-- main inputs -->

        <?php if ($this->formLayout == 'span12-span12'): ?>
    </div>
    <div class="row">
        <?php endif; ?>

    </div>

    <p class="alert">
        <?=
        "
        <?php 
            echo Yii::t('{$this->messageCatalogStandard}','Fields with <span class=\"required\">*</span> are required.');
                
            /**
             * @todo: We need the buttons inside the form, when a user hits <enter>
             */                
            echo ' '.CHtml::submitButton(Yii::t('{$this->messageCatalogStandard}', 'Save'), array(
                'class' => 'btn btn-primary',
                'style'=>'visibility: hidden;'                
            ));
                
        ?>";
        ?>

    </p>


    <?= "<?php \$this->endWidget() ?>"; ?>
    <?= "<?php {$this->provider()->generateHtml($this->modelClass, null, 'form-end')} ?>" ?>
</div> <!-- form -->
