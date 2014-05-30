<?php
//add editable provider
$this->providers = array('gtc.fullCrud.providers.EditableProvider');
$label = $this->pluralize($this->class2name($this->modelClass));
?><?=
"<?php
\$this->setPageTitle(
    Yii::t('{$this->messageCatalog}', '{$this->class2name($this->modelClass)}')
    . ' - '
    . Yii::t('{$this->messageCatalogStandard}', 'Create')
);

\$this->breadcrumbs[Yii::t('" . $this->messageCatalog . "', '$label')] = array('admin');
\$this->breadcrumbs[] = Yii::t('" . $this->messageCatalogStandard . "', 'Create');
" .    
'$cancel_buton = $this->widget("bootstrap.widgets.TbButton", array(
    #"label"=>Yii::t("' . $this->messageCatalogStandard . '","Cancel"),
    "icon"=>"chevron-left",
    "size"=>"large",
    "url"=>(isset($_GET["returnUrl"]))?$_GET["returnUrl"]:array("{$this->id}/admin"),
    "visible"=>(Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.View")),
    "htmlOptions"=>array(
                    "class"=>"search-button",
                    "data-toggle"=>"tooltip",
                    "title"=>Yii::t("' . $this->messageCatalogStandard . '","Cancel"),
                )
 ),true);
    
?>';
?>

<?= '<?php $this->widget("TbBreadcrumbs", array("links" => $this->breadcrumbs)) ?>'; ?>

<div class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="btn-group"><?="<?php echo \$cancel_buton;?>"?></div>
        <div class="btn-group">
            <h1>
                <i class="<?=$this->icon;?>"></i>
                <?php  echo "<?php echo Yii::t('" . $this->messageCatalog . "','Create " . $this->class2name($this->modelClass) . "');?>"?>
            </h1>
        </div>
    </div>
</div>

<?=
"<?php \$this->renderPartial('_form', array('model' => \$model, 'buttons' => 'create')); ?>";
?>

<div class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="btn-group"><?="<?php echo \$cancel_buton;?>"?></div>
        <div class="btn-group">
            <? echo '
                <?php  
                    $this->widget("bootstrap.widgets.TbButton", array(
                       "label"=>Yii::t("' . $this->messageCatalogStandard . '","Save"),
                       "icon"=>"icon-thumbs-up icon-white",
                       "size"=>"large",
                       "type"=>"primary",
                       "htmlOptions"=> array(
                            "onclick"=>"$(\'.crud-form form\').submit();",
                       ),
                       "visible"=> (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.View"))
                    )); 
                    ?>
            '?>
      
        </div>
    </div>
</div>