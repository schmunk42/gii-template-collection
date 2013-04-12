<div class="">
    <p class="alert">
        <?php echo "<?php echo Yii::t('" . $this->messageCatalog . "','Fields with <span class=\"required\">*</span> are required.');?> \n"; ?>
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

    <?php echo '?>'; ?>

 <div class="row">
     <div class="span8"> <!-- main inputs -->

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
            echo "\n";
            echo "    <div class=\"row-fluid input-block-level-form\">\n";
            echo "        <div class=\"span12\">\n";
            echo "            <?php " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>\n";
            echo "            <?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n";
            echo "            <?php echo \$form->error(\$model,'{$column->name}'); ?>\n";

            // renders a hint div, but leaves it empty, when the hint is not translated yet
            $placholder = "help." . $column->name . "";
            echo "            <?php if('" . $placholder . "' != \$help = Yii::t('" . $this->messageCatalog . "', '" . $placholder . "')) { \n";
            echo '                echo "<span class=\'help-block\'>$help</span>";';
            echo "            \n} ?>\n";
            echo "        </div>\n";
            echo "    </div>\n\n";
        }
    }

    foreach ($this->getRelations() as $key => $relation) {
        if ($relation[0] == 'CBelongsToRelation'
            || $relation[0] == 'CHasOneRelation'
            || $relation[0] == 'CManyManyRelation'
        ) {
            echo "    <div class=\"row-fluid input-block-level-form\">\n";
            echo "        <div class=\"span12\">\n";
            printf("        <label for=\"%s\"><?php echo Yii::t('" . $this->messageCatalog . "', '%s'); ?></label>\n", $key, ucfirst($key));
            echo "                <?php\n";
            echo "                ".$this->codeProvider->generateRelation($this->modelClass, $key, $relation);
            echo "\n              ?>\n";
            echo "        </div>\n";
            echo "    </div>\n\n";
        }
    }
    ?>
    </div> <!-- main inputs -->


    <div class="span4"> <!-- sub inputs -->

    </div> <!-- sub inputs -->
</div>


    <div class="form-actions">
        <?php
        echo "
    <?php
        echo CHtml::Button(Yii::t('" . $this->messageCatalog . "', 'Cancel'), array(
			'submit' => (isset(\$_GET['returnUrl']))?\$_GET['returnUrl']:array('" . strtolower($this->modelClass) . "/admin'),
			'class' => 'btn'
			));
        echo ' '.CHtml::submitButton(Yii::t('" . $this->messageCatalog . "', 'Save'), array(
            'class' => 'btn btn-primary'
            ));
    ?>\n";
        ?>
</div>

<?php echo "<?php \$this->endWidget() ?>"; ?>

</div> <!-- form -->