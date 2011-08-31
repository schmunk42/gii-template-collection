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

if(!isset($this->menu) || $this->menu === array())
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
			$relatedModel = CActiveRecord::model($relation[1]);
			$columns = $relatedModel->tableSchema->columns;
			
			#$suggestedfield = $this->suggestName($columns);
			
			$controller = GHelper::resolveController($relation);
			$value = "(\$model->{$key} !== null)?";
			$value .= "CHtml::link(\$model->{$key}->recordTitle, array('{$controller}/view','id'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey})).' '.";
			$value .= "CHtml::link('Edit', array('{$controller}/update','id'=>\$model->{$key}->{$relatedModel->tableSchema->primaryKey}), array('class'=>'edit'))";
			$value .= ":'n/a'";
			
			echo "\t\t\t'value'=>{$value},\n";
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
		
		$controller = GController::resolveRelationController($relation);
		$relatedModel = CActiveRecord::model($relation[1]);
		$pk = $relatedModel->tableSchema->primaryKey;
		
		if ($relation[0] == 'CManyManyRelation' || $relation[0] == 'CHasManyRelation') {
			#$model = CActiveRecord::model($relation[1]);
			#if (!$pk = $model->tableSchema->primaryKey)
			#	$pk = 'id';

			#$suggestedtitle = $this->suggestName($model->tableSchema->columns);
			echo '<h2>';
			echo "<?php echo CHtml::link(Yii::t('app','{relation}',array('{relation}'=>'" . ucfirst($key) . "')), array('".$controller."/admin'));?>";
			echo "</h2>\n";
			echo CHtml::openTag('ul');
			echo "<?php if (is_array(\$model->{$key})) foreach(\$model->{$key} as \$foreignobj) { \n
					echo '<li>';
					echo ''.CHtml::link(
						\$foreignobj->recordTitle,
						array('".$controller."/view', 'id' => \$foreignobj->{$pk}));\n
					};
					
						?>";
			echo CHtml::closeTag('ul');

			echo "<p><?php echo CHtml::link(
				Yii::t('app','Create'),
				array('".$controller."/create', '$relation[1]' => array('$relation[2]'=>\$model->{\$model->tableSchema->primaryKey}))
				);  ?></p>";
		}
		if ($relation[0] == 'CHasOneRelation') {
			$relatedModel = CActiveRecord::model($relation[1]);
			if (!$pk = $relatedModel->tableSchema->primaryKey)
				$pk = 'id';
			
			#$suggestedtitle = $this->suggestName($model->tableSchema->columns);
			echo '<h2>';
			echo "<?php echo CHtml::link(Yii::t('app','{relation}',array('{relation}'=>'".$relation[1]."')),'".$controller."/admin');?>";
			echo "</h2>\n";
			echo CHtml::openTag('ul');
			echo "<?php \$foreignobj = \$model->{$key}; \n
					if (\$foreignobj !== null) {
					echo '<li>';
					echo '#'.\$model->{$key}->{$pk}.' ';
					echo CHtml::link(\$model->{$key}->recordTitle, array('{$controller}/view','id'=>\$model->{$key}->{$pk}));\n							
					echo ' '.CHtml::link('Edit', array('{$controller}/update','id'=>\$model->{$key}->{$pk}));\n
					
					
					}
					?>";
			echo CHtml::closeTag('ul');
			echo "<p><?php if(\$model->{$key} === null) \$this->widget(
						'zii.widgets.jui.CJuiButton', 
						array(
							'name' => uniqid('add'),
							'url' => array('".$controller."/create', '$relation[1]' => array('$relation[2]'=>\$model->id)),
							'caption'=>'Add',
							'buttonType'=>'link',
							'options'=>array('icons'=>array('primary'=>'ui-icon-plus'),),
						)
					);  ?></p>";

		}
	}
?>
