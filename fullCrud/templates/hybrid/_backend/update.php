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

<?php echo "<?php\n"; ?>
$this->renderPartial('_form', array(
'model'=>$model));
?>

<?php echo "<?php\n"; ?>

/*
Code example to include an editable detail view:

<h2>
    <?php echo "<?php echo Yii::t('".$this->messageCatalog."','Editable Detail View')?>";?>
</h2>

<?php echo "<?php
\$this->widget('EditableDetailView', array(
    'data' => \$model,
    'url' => \$this->createUrl('editableSaver'),
));
?>\n"; ?>

*/
?>

<?php
$relations = CActiveRecord::model(Yii::import($this->model))->relations();
if ($relations !== array()): ?>

<?php
    foreach ($relations as $key => $relation) {
        $controller = str_replace("/", "", $this->resolveController($relation));
        $relatedModelClass = $relation[1];
        $relatedModel = CActiveRecord::model($relatedModelClass);
        $fk = $relation[2];
        $pk = $relatedModel->tableSchema->primaryKey;
        $suggestedfield = $this->provider()->suggestIdentifier($relatedModel);

        // TODO: currently composite PKs are omitted
        if (is_array($pk)) {
            continue;
        }
        if ($relation[0] == 'CBelongsToRelation') {
            continue;
        }

    if ($relation[0] == 'CHasManyRelation'): ?>

<h2>
    <?php
    echo "<?php echo Yii::t('" . $this->messageCatalog . "', '" . $this->pluralize($this->class2name($relatedModelClass)) . "'); ?> ";
    ?>
</h2>

<div class="btn-group">
<?php
    echo "<?php \$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => '', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'buttons'=>array(
        // TODO
        #array('label'=>Yii::t('" . $this->messageCatalog . "','Create'), 'icon'=>'icon-plus', 'url' => array('{$controller}/create','{$relatedModelClass}' => array('{$fk}'=>\$model->{$pk}), 'returnUrl' => Yii::app()->request->url), array('class'=>''))
    ),
));
?>";

?>
</div>

<?php echo "<?php\n"; ?>
$relatedSearchModel = $this->getRelatedSearchModel($model, '<?php echo $key; ?>');
$this->widget('TbGridView',
    array(
        'id'=>'<?php echo $controller; ?>-grid',
        'dataProvider'=>$relatedSearchModel->search(),
        'filter' => count($model-><?php echo $key; ?>) > 1 ? $relatedSearchModel : null,
        'pager' => array(
            'class' => 'TbPager',
            'displayFirstAndLast' => true,
        ),
    'columns'=>array(
        '<?php echo $pk; ?>',
        <?php
    $count = 0;
    foreach ($relatedModel->tableSchema->columns as $column) {

            // Primary key is not editable
            if ($column->name === $pk) {
                continue;
            }

            // Skip the foreign key
            if ($column->name === $fk) {
                continue;
            }

        if ($count == 7) {
        echo "        /*\n";
        }

        $count++;

        echo "        " . $this->provider()->generateColumn($relatedModelClass, $column, $controller) . ",\n";
    }

    if ($count >= 8) {
        echo "        */\n";
    }
    ?>
        array(
            'class'=>'TbButtonColumn',
            'viewButtonUrl' => "Yii::app()->controller->createUrl('<?php echo $controller; ?>/view', array('<?php echo $pk; ?>' => \$data-><?php echo $pk; ?>))",
            'updateButtonUrl' => "Yii::app()->controller->createUrl('<?php echo $controller; ?>/update', array('<?php echo $pk; ?>' => \$data-><?php echo $pk; ?>))",
            'deleteButtonUrl' => "Yii::app()->controller->createUrl('<?php echo $controller; ?>/delete', array('<?php echo $pk; ?>' => \$data-><?php echo $pk; ?>))",
        ),
    ),
));
?>

<?php
    endif;
    
        if (($relation[0] == 'CManyManyRelation')) {

            echo "<div class='well'>\n";
            echo "    <div class='row'>\n";

            echo "<div class='span3'><?php " . $this->provider()->generateRelationHeader($key, $relation, $controller) . " ?></div>";
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

            #echo CHtml::closeTag('div');
            echo "     </div> <!-- row -->\n";
            echo "</div> <!-- well -->\n";

        }

        if ($relation[0] == 'CHasOneRelation') {

            echo "<div class='well'>\n";
            echo "    <div class='row'>\n";

            $relatedModel = CActiveRecord::model($relation[1]);
            if (!$pk = $relatedModel->tableSchema->primaryKey) {
                $pk = 'id';
            }

            echo "<div class='span3'><?php " . $this->provider()->generateRelationHeader($relatedModel, $key, $relation) . " ?></div>";
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

            #echo CHtml::closeTag('div');
            echo "     </div> <!-- row -->\n";
            echo "</div> <!-- well -->\n";

        }
    }

endif; ?>
