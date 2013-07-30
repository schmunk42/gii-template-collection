<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs[Yii::t('" . $this->messageCatalog . "','$label')] = array('admin');\n";
echo "\$this->breadcrumbs[] = \$model->{$this->tableSchema->primaryKey};\n";
echo "?>";
?>

<?php echo '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

<h1>
    <?php
    echo "<?php echo Yii::t('" . $this->messageCatalog . "','" . $this->class2name($this->modelClass) . "')?>";
    echo " <small><?php echo Yii::t('" . $this->messageCatalog . "','View')?> #<?php echo \$model->" . $this->tableSchema->primaryKey . " ?></small>";
    ?>
</h1>



<?php echo '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>



<div class="row">
    <div class="span8">
        <h2>
            <?php echo "<?php echo Yii::t('" . $this->messageCatalog . "','Data')?>"; ?>
        </h2>

        <h3>
            <?php echo "<?php echo \$model->" . $this->suggestIdentifier(
                    CActiveRecord::model(Yii::import($this->model))
                ) . "?>"; ?>
        </h3>


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
                        $relatedModel   = CActiveRecord::model($relation[1]);
                        $suggestedfield = $this->suggestIdentifier($relatedModel);
                        $controller     = $this->resolveController($relation);

                        $value = "(\$model->{$key} !== null)?";
                        $value .= "CHtml::link(
                            '<i class=\"icon icon-circle-arrow-left\"></i> '.\$model->{$key}->{$suggestedfield},
                            array('{$controller}/view','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}),
                            array('class'=>'')).";
                        $value .= "' '.";
                        $value .= "CHtml::link(
                            '<i class=\"icon icon-pencil\"></i> ',
                            array('{$controller}/update','{$relatedModel->tableSchema->primaryKey}'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}),
                            array('class'=>''))";

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
                    echo "            'type'=>'url',\n";
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
        echo "),
        )); ?>";
        ?>

    </div>

    <div class="span4">
        <?php echo "
        <?php \$this->renderPartial('_view-relations',array('model'=>\$model)); ?>
        ";
        ?>
    </div>
</div>