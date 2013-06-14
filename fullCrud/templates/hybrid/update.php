<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs[Yii::t('".$this->messageCatalog."','$label')] = array('admin');\n";
echo "\$this->breadcrumbs[\$model->{\$model->tableSchema->primaryKey}] = array('view','id'=>\$model->{\$model->tableSchema->primaryKey});\n";
echo "\$this->breadcrumbs[] = Yii::t('".$this->messageCatalog."', 'Update');\n";
echo "?>";
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?php
    echo "<?php echo Yii::t('".$this->messageCatalog."','".$this->class2name($this->modelClass)."')?>";
    echo " <small><?php echo Yii::t('".$this->messageCatalog."','Update')?> #<?php echo \$model->" . $this->tableSchema->primaryKey . " ?></small>";
    ?>
</h1>

<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<?php
$relations = CActiveRecord::model(Yii::import($this->model))->relations();
if ($relations !== array()): ?>

<h2>
    <?php echo "<?php echo Yii::t('".$this->messageCatalog."','Relations')?>";?>
</h2>

<?php
    foreach ($relations as $key => $relation) {
        $controller = $this->codeProvider->resolveController($relation);
        $relatedModel = CActiveRecord::model($relation[1]);
        $pk = $relatedModel->tableSchema->primaryKey;
        $suggestedfield = $this->suggestName($relatedModel->tableSchema->columns);

        // TODO: currently composite PKs are omitted
        if (is_array($pk)) {
            continue;
        }
        if ($relation[0] == 'CBelongsToRelation') {
            continue;
        }

        echo "<div class='well'>\n";
        echo "    <div class='row'>\n";

        #echo CHtml::openTag('div');
        if (($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation')) {
            echo "<div class='span3'><?php " . $this->codeProvider->generateRelationHeader($relatedModel, $key, $relation) . " ?></div>";
            echo "<div class='span8'>
<?php
    echo '<span class=label>{$relation[0]}</span>';
    if (is_array(\$model->{$key})) {\n
        echo CHtml::openTag('ul');
            foreach(\$model->{$key} as \$relatedModel) {\n
                echo '<li>';
                echo CHtml::link(\$relatedModel->{$suggestedfield}, array('{$controller}/view','{$pk}'=>\$relatedModel->{$pk}), array('class'=>''));\n
                echo '</li>';
            }
        echo CHtml::closeTag('ul');
    }
?></div>";
            echo "\n";
        }


        if ($relation[0] == 'CHasOneRelation') {
            $relatedModel = CActiveRecord::model($relation[1]);
            if (!$pk = $relatedModel->tableSchema->primaryKey) {
                $pk = 'id';
            }

            echo "<div class='span3'><?php " . $this->codeProvider->generateRelationHeader($relatedModel, $key, $relation) . " ?></div>";
            echo "<div class='span8'>
<?php
    echo '<span class=label>{$relation[0]}</span>';
    \$relatedModel = \$model->{$key}; \n
    if (\$relatedModel !== null) {
        echo CHtml::openTag('ul');
        echo '<li>';
        echo CHtml::link(
            '#'.\$model->{$key}->{$pk}.' '.\$model->{$key}->{$suggestedfield},
            array('{$controller}/view','{$pk}'=>\$model->{$key}->{$pk}),
            array('class'=>''));\n
        echo '</li>';\n
        echo CHtml::closeTag('ul');
    }
?></div>";
        }
        #echo CHtml::closeTag('div');
        echo "     </div> <!-- row -->\n";
        echo "</div> <!-- well -->\n";
    }

endif;
?>

<h2>
    <?php echo "<?php echo Yii::t('".$this->messageCatalog."','Update Form')?>";?>
</h2>

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
'model'=>$model));
?>
