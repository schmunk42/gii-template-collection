 <div class="row">
     <div class="span8"> <!-- main inputs -->

    <?php
    foreach ($this->tableSchema->columns as $column) {

        // omit pk
        if ($column->autoIncrement) {
            continue;
        }

        // omit hasmany and manymany relations, they are rendered further below
        $columnRelation = array();
        foreach ($this->getRelations() as $key => $relation) {
            if ($relation[2] == $column->name) {
                if ($relation[0] !== 'CBelongsToRelation') {
                    continue 2;
                } else {
                    $columnRelation = compact("key","relation");
                }
            }
        }

        // render a view file if present in destination folder
        if ($columnView = $this->resolveColumnViewFile($column)) {
            echo "<?php      \$this->renderPartial('{$columnView}', array('model'=>\$model)) ?>";
            continue;
        }

        // assume timestamp attribute is automated
        $automatedAttributes = array(
            'timestamp',
        );

        // check for CTimestampBehavior to determine automated attributes
        $model = new $this->modelClass();
        $behaviors = $model->behaviors();
        if (isset($behaviors['CTimestampBehavior'])) {
            $behaviorObject = $model->asa('CTimestampBehavior');
            $automatedAttributes[] = $behaviorObject->createAttribute;
            $automatedAttributes[] = $behaviorObject->updateAttribute;
        }

        // render input
        if (!in_array($column->name, $automatedAttributes)) {
            echo "\n";

            if (isset($columnRelation["relation"]) && $columnRelation["relation"][0] === 'CBelongsToRelation') {
                // render belongsTo relation input
                echo "    <?php echo " . $this->generateRelationRow($this->modelClass, $column, $columnRelation["key"], $columnRelation["relation"]) . "; ?>\n";

                // render create button
                $controller = $this->codeProvider->resolveController($columnRelation["relation"]);
                $relatedModelClass = $columnRelation["relation"][1];
                $relatedModel = CActiveRecord::model($relatedModelClass);
                $fk = $columnRelation["relation"][2];
                $pk = $relatedModel->tableSchema->primaryKey;
                $suggestedfield = $this->suggestName($relatedModel->tableSchema->columns);

                echo "
                <?php
                \$formId = '{$this->class2id($this->modelClass)}-{$fk}-'.\uniqid().'-form';
                ?>
                ";?>

                    <div class="control-group">
                        <div class="controls">
                            <?php echo "<?php\n"; ?>
                            echo $this->widget('bootstrap.widgets.TbButton', array(
                                'label' => <?php echo "Yii::t('" . $this->messageCatalog . "', 'Create {model}', array('{model}' => Yii::t('" . $this->messageCatalog . "', '" . $this->class2name($relatedModelClass) . "')))"; ?>,
                                'icon' => 'icon-plus',
                                'htmlOptions' => array(
                                    'data-toggle' => 'modal',
                                    'data-target' => '#'.<?php echo '$formId'; ?>.'-modal',
                                ),
                                ), true);
                            ?>
                        </div>
                    </div>

                <?php

                // render modal create-forms into modal_forms clip (rendered by parent view outside active form elements)
                echo "<?php
                \$this->beginClip('modal:'.\$formId.'-modal');
                \$this->renderPartial('/{$controller}/_modal_form', array(
                    'formId' => \$formId,
                	'inputSelector' => '#{$this->modelClass}_{$fk}',
                    'model' => new {$relatedModelClass},
                    'pk' => '{$pk}',
                    'field' => '{$suggestedfield}',
                ));
                \$this->endClip();
                ?>
                ";

                ?>

            <?php
            } else {
                // render ordinary input row
                echo "    <?php echo " . $this->generateActiveRow($this->modelClass, $column) . "; ?>\n";
            }
        }
    }

    // render relation inputs
    foreach ($this->getRelations() as $key => $relation) {
        if ($relation[0] == 'CHasOneRelation'
            || $relation[0] == 'CManyManyRelation'
        ) {
            if ($relationView = $this->resolveRelationViewFile($relation)) {
                echo "      <?php \$this->renderPartial('{$relationView}', array('model'=>\$model)) ?>";
                continue;
            }

            echo "    <div class=\"row-fluid input-block-level-container\">\n";
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
