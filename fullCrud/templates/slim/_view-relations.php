<?php
$relations = CActiveRecord::model(Yii::import($this->model))->relations();
if (!empty($relations)) :
    ?>

    <h2>
        <?= "<?php echo Yii::t('{$this->messageCatalog}','Relations') ?>"; ?>
    </h2>

    <?php
    foreach ($relations as $key => $relation):

        $controller     = $this->resolveController($relation);
        $relatedModel   = CActiveRecord::model($relation[1]);
        $pk             = $relatedModel->tableSchema->primaryKey;
        $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);
        $scope          = (isset($relatedModel->scopes()['crud']))?'crud':'';

        // TODO: currently composite PKs are omitted
        if (is_array($pk)) {
            continue;
        }
        // BELONGS_TO relations are rendered in detail view
        if ($relation[0] == 'CBelongsToRelation') {
            continue;
        } elseif ($relation[0] == 'CHasOneRelation') {
            $recordsWrapper = "\$records = array(\$model->{$key}(array('limit'=>250, 'scopes' => '{$scope}'));"; // TODO: has one untested
        } else {
            $recordsWrapper = "\$records = \$model->{$key}(array('limit'=>250, 'scopes' => '{$scope}'));"; // TODO: move to ajax list
        }
        ?>

        <?= "<?php {$this->provider()->generateRelationHeader($key, $relation, $controller)} ?>" ?>

        <ul>

            <?=
            // relation view and update links
            "<?php
            {$recordsWrapper}
            if (is_array(\$records)) {
                foreach(\$records as \$i => \$relatedModel) {
                    echo '<li>';
                    echo CHtml::link(
                        '<i class=\"icon icon-arrow-right\"></i> '.\$relatedModel->{$suggestedfield},
                        array('{$controller}/view','{$pk}'=>\$relatedModel->{$pk})
                    );
                    echo CHtml::link(
                        ' <i class=\"icon icon-pencil\"></i>',
                        array('{$controller}/update','{$pk}'=>\$relatedModel->{$pk})
                    );
                    echo '</li>';
                }
            }
            ?>";
            ?>

        </ul>

    <?
    endforeach;

endif;
?>