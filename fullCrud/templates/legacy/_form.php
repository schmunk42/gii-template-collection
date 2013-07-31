<div class="form">
    <p class="note">
        <?php echo "<?php echo Yii::t('app','Fields with');?> <span class=\"required\">*</span> <?php echo Yii::t('app','are required');?>"; ?>
        .
    </p>

    <?php echo '<?php'; ?>

    $form=$this->beginWidget('CActiveForm', array(
    'id'=>'<?php echo $this->class2id($this->modelClass); ?>-form',
    'enableAjaxValidation'=><?php echo $this->validation == 1 || $this->validation == 3 ? 'true' : 'false'; ?>,
    'enableClientValidation'=><?php echo $this->validation == 2 || $this->validation == 3 ? 'true' : 'false'; ?>,
    ));

    echo $form->errorSummary($model);
    ?>

    <?php
    foreach ($this->tableSchema->columns as $column) {
        if ($column->isPrimaryKey) {
            continue;
        }

        // omit relations, they are rendered below
        foreach ($this->getRelations() as $key => $relation) {
            if ($relation[2] == $column->name) {
                continue 2;
            }
        }


        if (!$column->isForeignKey
            && $column->name != 'create_time'
            && $column->name != 'update_time'
            && $column->name != 'createtime'
            && $column->name != 'updatetime'
            && $column->name != 'timestamp'
        ) {
            echo "<div class=\"row\">\n";
            echo "<?php " . $this->provider()->generateActiveLabel($this->modelClass, $column) . "; ?>\n";
            echo "<?php " . $this->provider()->generateActiveField($this->modelClass, $column) . "; ?>\n";
            echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n";

            // renders a hint div, but leaves it empty, when the hint is not translated yet
            $placholder = "hint." . $this->modelClass . "." . $column->name . "";
            echo "<div class='hint'><?php if('" . $placholder . "' != \$hint = Yii::t('app', '" . $column->name . "')) echo \$hint; ?></div>\n";

            echo "</div>\n\n";
        }
    }

    foreach ($this->getRelations() as $key => $relation) {
        if ($relation[0] == 'CBelongsToRelation'
            || $relation[0] == 'CHasOneRelation'
            || $relation[0] == 'CManyManyRelation'
        ) {
            echo "<div class=\"row\">\n";
            /* printf("<label for=\"%s\"><?php echo Yii::t('app', 'Belonging').' '.Yii::t('app', '%s'); ?></label>\n", $relation[1], $relation[1]);
             */
            printf("<label for=\"%s\"><?php echo Yii::t('app', '%s'); ?></label>\n", $key, ucfirst($key));
            echo "<?php " . $this->provider()->generateRelationField($this->modelClass, $key, $relation) . "; ?><br />\n";
            echo "</div>\n\n";
        }
    }
    ?>

    <?php echo "<?php
echo CHtml::Button(Yii::t('app', 'Cancel'), array(
            'submit' => array('" . strtolower($this->modelClass) . "/admin')));
echo CHtml::submitButton(Yii::t('app', 'Save'));
\$this->endWidget(); ?>\n"; ?>
</div> <!-- form -->
