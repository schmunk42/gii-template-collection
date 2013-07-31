<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";

echo "\$this->breadcrumbs['$label'] = array('index');\n";
echo "\$this->breadcrumbs[] = \$model->{$this->tableSchema->primaryKey};\n";
?>

if(!$this->menu)
$this->menu=array(
array('label'=>Yii::t('app', 'Update') , 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
array('label'=>Yii::t('app', 'Delete') , 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>Yii::t('app', 'Create') , 'url'=>array('create')),
array('label'=>Yii::t('app', 'Manage') , 'url'=>array('admin')),
array('label'=>Yii::t('app', 'List') , 'url'=>array('index')),
);
?>

<h1><?php echo "<?php echo Yii::t('app', 'View');?>" ?> <?php echo $this->modelClass . " <?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php " ?>
$this->widget('zii.widgets.CDetailView', array(
'data'=>$model,
'attributes'=>array(
<?php
foreach ($this->tableSchema->columns as $column) {
    if ($column->isForeignKey) {
        echo "        array(\n";
        echo "            'name'=>'{$column->name}',\n";
        foreach ($this->relations as $key => $relation) {
            if ((($relation[0] == "CHasOneRelation") || ($relation[0] == "CBelongsToRelation")) && $relation[2] == $column->name) {
                $relatedModel = CActiveRecord::model($relation[1]);
                $columns      = $relatedModel->tableSchema->columns;

                $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);

                $controller = $this->resolveController($relation);
                $value      = "(\$model->{$key} !== null)?";
                $value .= "CHtml::link(\$model->{$key}->{$suggestedfield}, array('{$controller}/view','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey})).' '.";
                $value .= "CHtml::link(Yii::t('app','Update'), array('{$controller}/update','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}), array('class'=>'edit'))";
                $value .= ":'n/a'";

                echo "            'value'=>{$value},\n";
                echo "            'type'=>'html',\n";
            }
        }
        echo "        ),\n";
    } else {
        if (stristr($column->name, 'url')) {
            // TODO - experimental - move to provider class
            echo "array(";
            echo "            'name'=>'{$column->name}',\n";
            echo "            'type'=>'link',\n";
            echo "),\n";
        } else {
            if ($column->name == 'createtime'
                or $column->name == 'updatetime'
                or $column->name == 'timestamp'
            ) {
                echo "array(
                    'name'=>'{$column->name}',
                    'value' =>\$locale->getDateFormatter()->formatDateTime(\$model->{$column->name}, 'medium', 'medium')),\n";
            } else {
                echo "        '" . $column->name . "',\n";
            }
        }
    }
}
?>
),
)); ?>


<?php
foreach (CActiveRecord::model(Yii::import($this->model))->relations() as $key => $relation) {

    $controller     = $this->resolveController($relation);
    $relatedModel   = CActiveRecord::model($relation[1]);
    $pk             = $relatedModel->tableSchema->primaryKey;
    $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);

    // TODO: currently composite PKs are omitted
    if (is_array($pk)) {
        continue;
    }

    if (($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation')) {
        #$model = CActiveRecord::model($relation[1]);
        #if (!$pk = $model->tableSchema->primaryKey)
        #    $pk = 'id';
        #$suggestedtitle = $this->provider()->suggestIdentifier($model->tableSchema->columns);
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
        if (!$pk = $relatedModel->tableSchema->primaryKey) {
            $pk = 'id';
        }

        #$suggestedtitle = $this->provider()->suggestIdentifier($model->tableSchema->columns);
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
