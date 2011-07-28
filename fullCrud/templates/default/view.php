<?php
echo "<?php\n";
$nameColumn = GHelper::guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "if(!isset(\$this->breadcrumbs))\n
\$this->breadcrumbs=array(
'$label'=>array('index'),
	\$model->{$nameColumn},
	);\n";
?>

if(!isset($this->menu))
$this->menu=array(
		array('label'=>Yii::t('app', 'List') . ' <?php echo $this->modelClass; ?>', 'url'=>array('index')),
		array('label'=>Yii::t('app', 'Create') . ' <?php echo $this->modelClass; ?>', 'url'=>array('create')),
		array('label'=>Yii::t('app', 'Update') . ' <?php echo $this->modelClass; ?>', 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
		array('label'=>Yii::t('app', 'Delete') . ' <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>Yii::t('app', 'Manage') . ' <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
		);
?>

<h1><?php echo "<?php echo Yii::t('app', 'View');?>" ?> <?php echo $this->modelClass . " #<?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php
\$locale = CLocale::getInstance(Yii::app()->language);\n
"; ?> $this->widget('zii.widgets.CDetailView', array(
'data'=>$model,
	'attributes'=>array(
			<?php

			foreach ($this->tableSchema->columns as $column) {
			if ($column->isForeignKey) {
			echo "\t\tarray(\n";
			echo "\t\t\t'name'=>'{$column->name}',\n";
			foreach ($this->relations as $key => $relation) {
			if ((($relation[0] == "CHasOneRelation") || ($relation[0] == "CBelongsToRelation")) && $relation[2] == $column->name) {
			$columns = CActiveRecord::model($relation[1])->tableSchema->columns;
			$suggestedfield = $this->suggestName($columns);
			$controller = GHelper::resolveController($relation);
			echo "\t\t\t'value'=>(\$model->{$key} !== null)?CHtml::link(\$model->{$key}->{$suggestedfield->name}, array('{$controller}/view','id'=>\$model->{$key}->id)):'n/a',\n";
			echo "\t\t\t'type'=>'html',\n";
			}
			}
			echo "\t\t),\n";
			} else if (stristr($column->name, 'url')) {
			// TODO - experimental - move to provider class
			echo "array(";
			echo "\t\t\t'name'=>'{$column->name}',\n";
			echo "\t\t\t'type'=>'link',\n";
			echo "),\n";
			} else if($column->name == 'createtime'
					or $column->name == 'updatetime'
					or $column->name == 'timestamp') {
				echo "array(
					'name'=>'{$column->name}',
					'value' =>\$locale->getDateFormatter()->formatDateTime(\$model->{$column->name}, 'medium', 'medium')),\n";
			} else
				echo "\t\t'" . $column->name . "',\n";
			}
?>
),
	)); ?>


	<?php
	foreach (CActiveRecord::model(Yii::import($this->model))->relations() as $key => $relation) {
		if ($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation') {
			$model = CActiveRecord::model($relation[1]);
			if (!$pk = $model->tableSchema->primaryKey)
				$pk = 'id';

			$suggestedtitle = $this->suggestName($model->tableSchema->columns);
			echo '<h2>';
			echo "<?php echo CHtml::link(Yii::t('app','{relation}',array('{relation}'=>'" . ucfirst($key) . "')), array('" . $relation[1] . "/admin'));?>";
			echo "</h2>\n";
			echo CHtml::openTag('ul');
			printf("<?php foreach(\$model->%s as \$foreignobj) { \n
					printf('<li>%%s</li>', CHtml::link(\$foreignobj->%s, array('%s/view', 'id' => \$foreignobj->" . $pk . ")));\n
					} ?>", $key, $suggestedtitle->name, strtolower($relation[1]));
		echo CHtml::closeTag('ul');
		}
		if ($relation[0] == 'CHasOneRelation') {
			$model = CActiveRecord::model($relation[1]);
			if (!$pk = $model->tableSchema->primaryKey)
				$pk = 'id';

			$suggestedtitle = $this->suggestName($model->tableSchema->columns);
			echo '<h2>';
			echo "<?php echo CHtml::link(Yii::t('app','{relation}',array('{relation}'=>'{$key}')),'XXX');?>";
			echo "</h2>\n";
			echo CHtml::openTag('ul');
			printf("<?php
					if(\$model->%s !== null) printf('<li>%%s</li>', CHtml::link(\$model->{$key}->%s, array('%s/view', 'id' => \$model->{$key}->%s)));\n
					?>", $key, $suggestedtitle->name, strtolower($relation[1]), $pk);
			echo CHtml::closeTag('ul');
		}
	}
?>
