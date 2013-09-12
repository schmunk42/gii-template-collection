<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs[Yii::t('".$this->messageCatalog."','$label')] = array('admin');\n";
echo "\$this->breadcrumbs[] = \$model->{$this->tableSchema->primaryKey};\n";
echo "?>";
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?php
    echo "<?php echo Yii::t('".$this->messageCatalog."','".$this->class2name($this->modelClass)."')?>";
    echo " <small><?php echo Yii::t('".$this->messageCatalog."','View')?> #<?php echo \$model->" . $this->tableSchema->primaryKey . " ?></small>";
    ?>
</h1>



<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<?php
echo "    <b><?php echo CHtml::encode(\$model->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
echo "    <?php echo CHtml::link(CHtml::encode(\$model->{$this->tableSchema->primaryKey}), array('view', '{$this->tableSchema->primaryKey}'=>\$model->{$this->tableSchema->primaryKey})); ?>\n    <br />\n\n";
$count = 0;
foreach ($this->tableSchema->columns as $column) {
    if ($column->isPrimaryKey)
    continue;
    if (++$count == 7)
    echo "    <?php /*\n";
    echo "    <b><?php echo CHtml::encode(\$model->getAttributeLabel('{$column->name}')); ?>:</b>\n";
    if ($column->name == 'createtime'
    or $column->name == 'updatetime'
    or $column->name == 'timestamp') {
    echo "    echo Yii::app()->getDateFormatter()->formatDateTime(\$model->{$column->name}, 'medium', 'medium'); ?>\n    <br />\n\n";
    } else {
    echo "    <?php echo CHtml::encode(\$model->{$column->name}); ?>\n    <br />\n\n";
    }
}
if ($count >= 7)
    echo "    */ ?>\n";
?>

<?php
foreach (CActiveRecord::model(Yii::import($this->model))->relations() as $key => $relation) {

    $controller = $this->resolveController($relation);
    $relatedModel = CActiveRecord::model($relation[1]);
    $pk = $relatedModel->tableSchema->primaryKey;
    $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);

    // TODO: currently composite PKs are omitted
    if (is_array($pk))
        continue;

    if (($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation')) {
        #$model = CActiveRecord::model($relation[1]);
        #if (!$pk = $model->tableSchema->primaryKey)
        #    $pk = 'id';
        #$suggestedtitle = $this->suggestName($model->tableSchema->columns);
        echo '<h2>';
        echo "<?php echo CHtml::link(Yii::t('app','" . ucfirst($key) . "'), array('" . $controller . "/admin'));?>";
        echo "</h2>\n";
        echo CHtml::openTag('ul');
        echo "
                <?php if (is_array(\$model->{$key})) foreach(\$model->{$key} as \$foreignobj) { \n
                    echo '<li>';
                    echo CHtml::link(\$foreignobj->$suggestedfield, array('{$controller}/view','{$pk}'=>\$foreignobj->{$pk}));\n
                        echo ' '.CHtml::link(Yii::t('app','Update'), array('{$controller}/update','{$pk}'=>\$foreignobj->{$pk}), array('class'=>'edit'));\n
                }
            ?>";
        echo CHtml::closeTag('ul');

        echo "<p><?php echo CHtml::link(
                Yii::t('app','Create'),
                array('" . $controller . "/create', '$relation[1]' => array('$relation[2]'=>\$model->{\$model->tableSchema->primaryKey}))
                    );  ?></p>";
    }
    if ($relation[0] == 'CHasOneRelation') {
        $relatedModel = CActiveRecord::model($relation[1]);
        if (!$pk = $relatedModel->tableSchema->primaryKey)
            $pk = 'id';

#$suggestedtitle = $this->suggestName($model->tableSchema->columns);
        echo '<h2>';
        echo "<?php echo CHtml::link(Yii::t('app','" . $relation[1] . "'), array('" . $controller . "/admin'));?>";
        echo "</h2>\n";
        echo CHtml::openTag('ul');
        echo "<?php \$foreignobj = \$model->{$key}; \n
                if (\$foreignobj !== null) {
                    echo '<li>';
                    echo '#'.\$model->{$key}->{$pk}.' ';
                    echo CHtml::link(\$model->{$key}->$suggestedfield, array('{$controller}/view','{$pk}'=>\$model->{$key}->{$pk}));\n
                        echo ' '.CHtml::link(Yii::t('app','Update'), array('{$controller}/update','{$pk}'=>\$model->{$key}->{$pk}), array('class'=>'edit'));\n


                }
            ?>";
        echo CHtml::closeTag('ul');
        echo "<p><?php if(\$model->{$key} === null) echo CHtml::link(
                Yii::t('app','Create'),
                array('" . $controller . "/create', '$relation[1]' => array('$relation[2]'=>\$model->{\$model->tableSchema->primaryKey}))
                    );  ?></p>";
    }
}
?>

<h2>
    <?php echo "<?php echo Yii::t('".$this->messageCatalog."','Data')?>";?>
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

                    $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);
                    $controller = $this->resolveController($relation);
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

