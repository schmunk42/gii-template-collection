<div class="form">
    <p class="note">
        <?php echo "<?php echo Yii::t('".$this->messageCatalog."','Fields with');?> <span class=\"required\">*</span> <?php echo Yii::t('".$this->messageCatalog."','are required');?>"; ?>
        .
    </p>


    <?php echo "<?php
    \$this->widget('echosen.EChosen',
        array('target'=>'select')
    );
?>"; ?>
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
        if ($column->autoIncrement) {
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
            echo "<?php " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>\n";
            echo "<?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n";
            echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n";

            // renders a hint div, but leaves it empty, when the hint is not translated yet
            $placholder = "help." . $column->name . "";
            echo "<?php if('" . $placholder . "' != \$help = Yii::t('".$this->messageCatalog."', '" . $placholder . "')) { \n";
            echo '    echo "<span class=\'help-block\'>$help</span>";';
            echo "\n} ?>";

            echo "</div>\n\n";
        }
    }

    foreach ($this->getRelations() as $key => $relation) {
        if ($relation[0] == 'CBelongsToRelation'
            || $relation[0] == 'CHasOneRelation'
            || $relation[0] == 'CManyManyRelation'
        ) {
            echo "<div class=\"row\">\n";
            printf("<label for=\"%s\"><?php echo Yii::t('".$this->messageCatalog."', '%s'); ?></label>\n", $key, ucfirst($key));
            echo "<?php " . $this->codeProvider->generateRelation($this->modelClass, $key, $relation) . "; ?><br />\n";
            echo "</div>\n\n";
        }
    }
    ?>

</div> <!-- form -->
<div class="form-actions">
    <?php
    echo "
    <?php
echo CHtml::Button(Yii::t('".$this->messageCatalog."', 'Cancel'), array(
			'submit' => (isset(\$_GET['returnUrl']))?\$_GET['returnUrl']:array('" . strtolower($this->modelClass) . "/admin'),
			'class' => 'btn'
			));
echo ' '.CHtml::submitButton(Yii::t('".$this->messageCatalog."', 'Save'), array(
            'class' => 'btn btn-primary'
));
\$this->endWidget(); ?>\n";
    ?>
</div>
