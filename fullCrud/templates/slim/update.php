<?=
"<?php
\$this->setPageTitle(
        Yii::t('{$this->messageCatalog}', '{$this->class2name($this->modelClass)}')
        . ' - '
        . Yii::t('{$this->messageCatalogStandard}', 'Update')
        . ': '   
        . \$model->getItemLabel()
);    
\$this->breadcrumbs[Yii::t('{$this->messageCatalog}','{$this->pluralize($this->class2name($this->modelClass))}')] = array('admin');
\$this->breadcrumbs[\$model->{\$model->tableSchema->primaryKey}] = array('view','id' => \$model->{\$model->tableSchema->primaryKey});
\$this->breadcrumbs[] = Yii::t('{$this->messageCatalogStandard}', 'Update');
?>
";?>

<?= '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

    <h1>
        <?=
        "
        <?php echo Yii::t('{$this->messageCatalog}','{$this->class2name($this->modelClass)}'); ?>
        <small>
            <?php echo \$model->{$this->provider()->suggestIdentifier($this->modelClass)} ?>

        </small>

        ";
        ?>

    </h1>

<?= '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>

<?=
"
<?php
    \$this->renderPartial('_form', array('model' => \$model));
?>
"
?>

<?= '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>