<div class="crud-form">

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
    ?>

    <div class="row">
        <div class="<?= ($this->formLayout == 'two-columns') ? 'span7' : 'span12' ?>">
            <h2>
                <?= "<?php echo Yii::t('{$this->messageCatalogStandard}','Data')?>" ?>
                <small>
                    #<?= "<?php echo \$model->{$this->tableSchema->primaryKey} ?>" ?>
                </small>

            </h2>


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

        <?php if ($this->formLayout == 'one-column'): ?>
    </div>
    <div class="row">
        <?php endif; ?>

        <div class="<?= ($this->formLayout == 'two-columns') ? 'span5' : 'span12' ?>"><!-- sub inputs -->
            <h2>
                <?= "<?php echo Yii::t('" . $this->messageCatalogStandard . "','Relations')?>"; ?>
            </h2>
            <div class="well">
            <?php foreach ($this->getRelations() as $key => $relation) : ?>
                <?php if (($relation[0] == "CBelongsToRelation") || !$this->provider()->generateRelationField($this->modelClass, $key, $relation) ) {
                    continue;
                } ?>
                <?=
                // relations
                "
                <h3>
                    <?php echo ((\$t = Yii::t('{$this->messageCatalog}', 'relation.".ucfirst($key)."')) != 'relation.".ucfirst($key)."')?\$t:substr(\$t,9) . ' '; ?>
                </h3>
                <?php {$this->provider()->generateRelationField($this->modelClass, $key, $relation)} ?>

                "
                ?>
            <?php endforeach; ?>
            </div>
        </div>
        <!-- sub inputs -->
    </div>

    <p class="alert">
        <?=
        "<?php echo Yii::t('{$this->messageCatalogStandard}','Fields with <span class=\"required\">*</span> are required.');?>";
        ?>

    </p>

    <!-- TODO: We need the buttons inside the form, when a user hits <enter> -->
    <div class="form-actions" style="visibility: hidden; height: 1px">
        <?=
        "
        <?php
            echo CHtml::Button(
            Yii::t('{$this->messageCatalogStandard}', 'Cancel'), array(
                'submit' => (isset(\$_GET['returnUrl']))?\$_GET['returnUrl']:array('{$this->controllerID}/admin'),
                'class' => 'btn'
            ));
            echo ' '.CHtml::submitButton(Yii::t('{$this->messageCatalogStandard}', 'Save'), array(
                'class' => 'btn btn-primary'
            ));
        ?>";
        ?>

    </div>

    <?= "<?php \$this->endWidget() ?>"; ?>

</div> <!-- form -->
