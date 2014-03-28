<?=
"<?php
    \$this->setPageTitle(
        Yii::t('{$this->messageCatalog}', '{$this->class2name($this->modelClass)}')
        . ' - '
        . Yii::t('{$this->messageCatalogStandard}', 'View')
        . ': '   
        . \$model->getItemLabel()            
);    
\$this->breadcrumbs[Yii::t('{$this->messageCatalog}','{$this->pluralize($this->class2name($this->modelClass))}')] = array('admin');
\$this->breadcrumbs[\$model->{\$model->tableSchema->primaryKey}] = array('view','id' => \$model->{\$model->tableSchema->primaryKey});
\$this->breadcrumbs[] = Yii::t('{$this->messageCatalogStandard}', 'View');
?>
";?>

<?= '<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>'; ?>

    <h1>
        <?=
        "<?php echo Yii::t('" . $this->messageCatalog . "','" . $this->class2name($this->modelClass) . "')?>
        <small>
            <?php echo \$model->{$this->provider()->suggestIdentifier($this->modelClass)} ?>

        </small>

    "?>
    </h1>



<?= '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>



<div class="row">
    <div class="<?= ($this->formLayout == 'two-columns') ? 'span7' : 'span12' ?>">
        <h2>
            <?= "<?php echo Yii::t('" . $this->messageCatalogStandard . "','Data')?>"; ?>
            <small>
                #<?= "<?php echo \$model->{$this->tableSchema->primaryKey} ?>" ?>
            </small>
        </h2>

        <?=
        "<?php
        \$this->widget(
            'TbDetailView',
            array(
                'data' => \$model,
                'attributes' => array(
        ";
        ?>
        <?php
        foreach ($this->tableSchema->columns as $column) {
            if ($this->provider()->skipColumn($this->modelClass, $column)) {
                continue;
            }
            echo $this->provider()->generateAttribute($this->model, $column);
        }
        ?>
        <?=
        "   ),
        )); ?>"?>

    </div>

<?php if ($this->formLayout == 'one-column'): ?>
    </div>
    <div class="row">
<?php endif; ?>

    <div class="<?= ($this->formLayout == 'two-columns') ? 'span5' : 'span12' ?>">
        <div class="well">
            <?= "<?php \$this->renderPartial('_view-relations',array('model' => \$model)); ?>"; ?>
        </div>
    </div>
</div>

<?= '<?php $this->renderPartial("_toolbar", array("model"=>$model)); ?>'; ?>