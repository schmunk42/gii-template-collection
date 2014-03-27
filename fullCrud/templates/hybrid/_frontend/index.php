<?php
$label = $this->pluralize($this->class2name($this->modelClass));
?><?=
"<?php
\$this->breadcrumbs[Yii::t('" . $this->messageCatalog . "', '$label')] = array('index');
\$this->breadcrumbs[] = Yii::t('" . $this->messageCatalog . "', 'Index');
?>";
?>

<?= '<?php $this->widget("TbBreadcrumbs", array("links" => $this->breadcrumbs)) ?>'; ?>

<?=
"<?php
if (!isset(\$this->menu) || \$this->menu === array()) {
    \$this->menu = array(
        array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
        array('label' => Yii::t('app', 'Manage'), 'url' => array('admin')),
    );
}
?>";
?>

    <h1><?= "<?php echo Yii::t('" . $this->messageCatalog . "', '" . $label . "'); ?>"; ?></h1>

<?=
"<?php
\$this->widget('zii.widgets.CListView', array(
    'dataProvider' => \$dataProvider,
    'itemView' => '_view',
));
?>";
?>