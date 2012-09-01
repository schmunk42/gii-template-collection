<?php
$label = $this->pluralize($this->class2name($this->modelClass));

echo "<?php\n";
echo "\$this->breadcrumbs['$label'] = array('index');\n";
echo "\$this->breadcrumbs[] = Yii::t('app', 'Index');\n";
?>

if(!isset($this->menu) || $this->menu === array())
$this->menu=array(
array('label'=>Yii::t('app', 'Create'), 'url'=>array('create')),
array('label'=>Yii::t('app', 'Manage'), 'url'=>array('admin')),
);
?>

<h1><?php echo $label; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>
