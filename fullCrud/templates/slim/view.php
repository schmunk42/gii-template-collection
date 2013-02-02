<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs['$label'] = array('admin');\n";
echo "\$this->breadcrumbs[] = \$model->{$this->identificationColumn};\n";
echo "?>";
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?php
    echo $this->class2name($this->modelClass);
    echo " <small>View #<?php echo \$model->" . $this->tableSchema->primaryKey . " ?></small>";
    ?>
</h1>



<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>


<h2>
    Data
</h2>

<p>
    <?php
    echo "<?php
    \$this->widget('TbDetailView', array(
    'data'=>\$model,
    'attributes'=>array(
    ";
    foreach ($this->tableSchema->columns as $column) {
        if ($column->isForeignKey) {
            echo "        array(\n";
            echo "            'name'=>'{$column->name}',\n";
            foreach ($this->relations as $key => $relation) {
                if ((($relation[0] == "CHasOneRelation") || ($relation[0] == "CBelongsToRelation")) && $relation[2] == $column->name) {
                    $relatedModel = CActiveRecord::model($relation[1]);
                    $columns = $relatedModel->tableSchema->columns;

                    $suggestedfield = $this->suggestName($columns);

                    $controller = $this->codeProvider->resolveController($relation);
                    $value = "(\$model->{$key} !== null)?";
                    $value .= "'<span class=label>" . $relation[0] . "</span><br/>'.";
                    $value .= "CHtml::link(\$model->{$key}->{$suggestedfield}, array('{$controller}/view','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}), array('class'=>'btn'))";
                    #$value .= "' '.";
                    #$value .= "CHtml::link(Yii::t('app','Update'), array('{$controller}/update','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}), array('class'=>'btn'))";
                    $value .= ":'n/a'";

                    echo "            'value'=>{$value},\n";
                    echo "            'type'=>'html',\n";
                }
            }
            echo "        ),\n";
        }
        else {
            if (stristr($column->name, 'url')) {
                // TODO - experimental - move to provider class
                echo "array(";
                echo "            'name'=>'{$column->name}',\n";
                echo "            'type'=>'url',\n";
                echo "),\n";
            }
            else {
                if ($column->name == 'createtime'
                    or $column->name == 'updatetime'
                    or $column->name == 'timestamp'
                ) {
                    echo "array(
					'name'=>'{$column->name}',
					'value' =>\$locale->getDateFormatter()->formatDateTime(\$model->{$column->name}, 'medium', 'medium')),\n";
                }
                else {
                    echo "        '" . $column->name . "',\n";
                }
            }
        }
    }
    echo "),
        )); ?>";
    ?>
</p>

<?php
$relations = CActiveRecord::model(Yii::import($this->model))->relations();
if ($relations !== array()): ?>

<h2>
    Relations
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