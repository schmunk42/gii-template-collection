<?php
$relations = CActiveRecord::model(Yii::import($this->model))->relations();
if (!empty($relations)) :
    ?>

    <h2>
        <?= "<?php echo Yii::t('{$this->messageCatalog}','Relations') ?>"; ?>
    </h2>

    <?php
    foreach ($relations as $key => $relation) {
        $controller     = $this->resolveController($relation);
        $relatedModel   = CActiveRecord::model($relation[1]);
        $pk             = $relatedModel->tableSchema->primaryKey;
        $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);

        // TODO: currently composite PKs are omitted
        if (is_array($pk)) {
            continue;
        }
        // BELONGS_TO relations are rendered in detail view
        if ($relation[0] == 'CBelongsToRelation') {
            continue;
        }

        // start output -->
        echo "


        <!-- {$relation[0]} {$relation[1]} BEGIN -->
        <div class='control-group'>";

        // *_MANY relations
        if (($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation')) {
            echo "
            <p>
<?php ".$this->provider()->generateRelationHeader($key, $relation, $controller)." ?>
            </p>
            <ul class='relations'>
<?php
    if (is_array(\$model->{$key})) {
            foreach(\$model->{$key} as \$relatedModel) {
                echo '<li>';
                echo CHtml::link(
                    '<i class=\"icon icon-arrow-right\"></i> '.\$relatedModel->{$suggestedfield},
                    array('{$controller}/view','{$pk}'=>\$relatedModel->{$pk}), array('class'=>'')
                );
                echo CHtml::link(
                    ' <i class=\"icon icon-pencil\"></i>',
                    array('{$controller}/update','{$pk}'=>\$relatedModel->{$pk}), array('class'=>'')
                );
                echo '</li>';
            }
    }
?>
            </ul>";
        } // endif *_MANY

        // HAS_ONE relations
        if ($relation[0] == 'CHasOneRelation') {
            $relatedModel = CActiveRecord::model($relation[1]);
            if (!$pk = $relatedModel->tableSchema->primaryKey) {
                $pk = 'id';
            }

            echo "
            <p>
<?php ".$this->provider()->generateRelationHeader($key, $relation, $controller)."?>
            </p>";

            echo "
<?php
    \$relatedModel = \$model->{$key};
    if (\$relatedModel !== null) {
        echo CHtml::openTag('ul');
        echo '<li>';
        echo CHtml::link(
            '#'.\$model->{$key}->{$pk}.' '.\$model->{$key}->{$suggestedfield},
            array('{$controller}/view','{$pk}'=>\$model->{$key}->{$pk}),
            array('class'=>''));
        echo '</li>';
        echo CHtml::closeTag('ul');
    }
?>";
        } // endif HAS_ONE


        echo "
        </div> <!-- control-group -->\n";
    }

endif;
?>