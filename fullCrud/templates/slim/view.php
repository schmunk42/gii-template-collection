<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs['$label'] = array('index');\n";
echo "\$this->breadcrumbs[] = \$model->{$this->identificationColumn};\n";
echo "?>";
?>

<h1>
    <?php echo "<?php echo Yii::t('app', 'View');?>" ?> <?php echo $this->modelClass . " <?php echo \$model->{$this->identificationColumn}; ?>"; ?>
</h1>

<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<p>
    <?php
    echo "<?php
    \$this->widget('ext.crisu83.yii-bootstrap.widgets.TbDetailView', array(
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
                    $value .= "CHtml::link(\$model->{$key}->{$suggestedfield}, array('{$controller}/view','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}), array('class'=>'btn')).' '.";
                    $value .= "CHtml::link(Yii::t('app','Update'), array('{$controller}/update','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}), array('class'=>'btn'))";
                    $value .= ":'n/a'";

                    echo "            'value'=>{$value},\n";
                    echo "            'type'=>'html',\n";
                }
            }
            echo "        ),\n";
        } else if (stristr($column->name, 'url')) {
            // TODO - experimental - move to provider class
            echo "array(";
            echo "            'name'=>'{$column->name}',\n";
            echo "            'type'=>'link',\n";
            echo "),\n";
        } else if ($column->name == 'createtime'
            or $column->name == 'updatetime'
            or $column->name == 'timestamp') {
            echo "array(
					'name'=>'{$column->name}',
					'value' =>\$locale->getDateFormatter()->formatDateTime(\$model->{$column->name}, 'medium', 'medium')),\n";
        } else
            echo "        '" . $column->name . "',\n";
    }
    echo "),
        )); ?>";
    ?>
</p>

<h2>Relations</h2>

<?php
foreach (CActiveRecord::model(Yii::import($this->model))->relations() as $key => $relation) {
    $controller = $this->codeProvider->resolveController($relation);
    $relatedModel = CActiveRecord::model($relation[1]);
    $pk = $relatedModel->tableSchema->primaryKey;
    $suggestedfield = $this->suggestName($relatedModel->tableSchema->columns);

    // TODO: currently composite PKs are omitted
    if (is_array($pk))
        continue;

    echo "<?php ".$this->codeProvider->generateRelationHeader($relatedModel, $key, $relation)." ?>";

    echo CHtml::openTag('div');
    if (($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation')) {
        echo "
<?php
    if (is_array(\$model->{$key})) {\n
        echo CHtml::openTag('ul');
            foreach(\$model->{$key} as \$relatedModel) {\n
                echo '<li>';
                echo CHtml::link(\$relatedModel->$suggestedfield, array('{$controller}/view','{$pk}'=>\$relatedModel->{$pk}), array('class'=>''));\n
                echo '</li>';
            }
        echo CHtml::closeTag('ul');
    }
?>";
        echo "\n";
    }


    if ($relation[0] == 'CHasOneRelation') {
        $relatedModel = CActiveRecord::model($relation[1]);
        if (!$pk = $relatedModel->tableSchema->primaryKey)
            $pk = 'id';

        echo "
<?php
    \$relatedModel = \$model->{$key}; \n
    if (\$relatedModel !== null) {
        echo CHtml::openTag('ul');
        echo '<li>';
        echo CHtml::link(
            '#'.\$model->{$key}->{$pk}.' '.\$model->{$key}->$suggestedfield,
            array('{$controller}/view','{$pk}'=>\$model->{$key}->{$pk}),
            array('class'=>''));\n
        echo '</li>';\n
        echo CHtml::closeTag('ul');
    }
?>";
    }
    echo CHtml::closeTag('div');
}
?>