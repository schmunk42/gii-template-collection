<?= "<?php Yii::beginProfile('{$this->modelClass}.view.toolbar'); ?>"; ?>

<?php $pk = CActiveRecord::model($this->modelClass)->tableSchema->primaryKey ?>

<?=
'<?php
    $showDeleteButton = (Yii::app()->request->getParam("' . $pk . '"))?true:false;
    $showManageButton = true;
    $showCreateButton = true;
    $showUpdateButton = true;
    $showCancelButton = true;
    $showSaveButton   = true;
    $showViewButton   = true;

    switch($this->action->id){
        case "admin":
            $showCancelButton = false;
            $showSaveButton   = false;
            $showViewButton   = false;
            $showUpdateButton = false;
            break;
        case "update":
            $showCreateButton = false;
            $showUpdateButton = false;
            break;
        case "create":
            $showCreateButton = false;
            $showViewButton   = false;
            $showUpdateButton = false;
            break;
        case "view":
            $showViewButton   = false;
            $showSaveButton   = false;
            $showCreateButton = false;
            break;
    }
?>';
?>

<div class="clearfix">
    <div class="btn-toolbar pull-right">
        <!-- relations -->
        <?php
        $model = new $this->modelClass;
        if ($model->relations() !== array()):
            ?>
            <div class="btn-group">
                <?=
                "<?php  if (!Yii::app()->user->checkAccess('" . $this->getRightsPrefix() . ".SimpleUi') || Yii::app()->user->checkAccess('Admin')) {
                        \$this->widget('bootstrap.widgets.TbButtonGroup', array(
                       'size'=>'large',
                       'buttons' => array(
                               array(
                                #'label'=>Yii::t('" . $this->messageCatalogStandard . "','Relations'),
                                'icon'=>'icon-random',
                                'items'=>array(";

                // render relation links
                foreach ($model->relations() AS $key => $relation) {
                    if ($relation[1] == get_class($model)) {
                        continue;
                    }
                    $replace = array(
                        'CBelongsToRelation' => 'circle-arrow-left',
                        'CManyManyRelation'  => 'resize-horizontal',
                        'CHasManyRelation'   => 'arrow-right',
                        'CHasOneRelation'    => 'circle-arrow-right',
                    );
                    echo "array(
                    'icon' => '" . strtr(
                            $relation[0],
                            $replace
                        ) . "','label' => Yii::t('{$this->messageCatalog}','relation." . ucfirst(
                            $key
                        ) . "'), 'url' =>array('" . $this->resolveController($relation) . "/admin')),";
                }

                echo "
            )
          ),
        ),
    ));
}
?>";

                ?>
            </div>

        <?php endif; ?>

        <div class="btn-group">
            <?=
            '<?php
             $this->widget("bootstrap.widgets.TbButton", array(
                           "label"=>Yii::t("' . $this->messageCatalogStandard . '","Manage"),
                           "icon"=>"icon-list-alt",
                           "size"=>"large",
                           "url"=>array("admin"),
                           "visible"=>$showManageButton && (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.View")) && (!Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.SimpleUi") || Yii::app()->user->checkAccess("Admin"))
                        ));
         ?>'?>
        </div>
    </div>

    <div class="btn-toolbar pull-left">
        <div class="btn-group">
            <?=
            '<?php
                   $this->widget("bootstrap.widgets.TbButton", array(
                       #"label"=>Yii::t("' . $this->messageCatalogStandard . '","Cancel"),
                       "icon"=>"chevron-left",
                       "size"=>"large",
                       "url"=>(isset($_GET["returnUrl"]))?$_GET["returnUrl"]:array("{$this->id}/admin"),
                       "visible"=>$showCancelButton && (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.View")),
                       "htmlOptions"=>array(
                                       "class"=>"search-button",
                                       "data-toggle"=>"tooltip",
                                       "title"=>Yii::t("' . $this->messageCatalogStandard . '","Cancel"),
                                   )
                    ));
                   $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalogStandard . '","Create"),
                        "icon"=>"icon-plus",
                        "size"=>"large",
                        "type"=>"success",
                        "url"=>array("create"),
                        "visible"=>$showCreateButton && (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.Create"))
                   ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalogStandard . '","Delete"),
                        "type"=>"danger",
                        "icon"=>"icon-trash icon-white",
                        "size"=>"large",
                        "htmlOptions"=> array(
                            "submit"=>array("delete","' . $pk . '"=>$model->{$model->tableSchema->primaryKey}, "returnUrl"=>(Yii::app()->request->getParam("returnUrl"))?Yii::app()->request->getParam("returnUrl"):$this->createUrl("admin")),
                            "confirm"=>Yii::t("' . $this->messageCatalogStandard . '","Do you want to delete this item?")
                        ),
                        "visible"=> $showDeleteButton && (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.Delete"))
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        #"label"=>Yii::t("' . $this->messageCatalogStandard . '","Update"),
                        "icon"=>"icon-edit icon-white",
                        "type"=>"primary",
                        "size"=>"large",
                        "url"=>array("update","' . $pk . '"=>$model->{$model->tableSchema->primaryKey}),
                        "visible"=> $showUpdateButton &&  (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.Update"))
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        #"label"=>Yii::t("' . $this->messageCatalogStandard . '","View"),
                        "icon"=>"icon-eye-open",
                        "size"=>"large",
                        "url"=>array("view","' . $pk . '"=>$model->{$model->tableSchema->primaryKey}),
                        "visible"=>$showViewButton &&  (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.View")),
                        "htmlOptions"=>array(
                                      "data-toggle"=>"tooltip",
                                      "title"=>Yii::t("' . $this->messageCatalogStandard . '","View Mode"),
                        )
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                       "label"=>Yii::t("' . $this->messageCatalogStandard . '","Save"),
                       "icon"=>"icon-thumbs-up icon-white",
                       "size"=>"large",
                       "type"=>"primary",
                       "htmlOptions"=> array(
                            "onclick"=>"$(\'.crud-form form\').submit();",
                       ),
                       "visible"=>$showSaveButton &&  (Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.*") || Yii::app()->user->checkAccess("' . $this->getRightsPrefix() . '.View"))
                    ));
             ?>';
            ?>
        </div>
        <?= "<?php if(\$this->action->id == 'admin'): ?>" ?>
        <div class="btn-group">
            <?=
            '
            <?php
                $this->widget(
                       "bootstrap.widgets.TbButton",
                       array(
                           #"label"=>Yii::t("' . $this->messageCatalogStandard . '","Search"),
                                   "icon"=>"icon-search",
                                   "size"=>"large",
                                   "htmlOptions"=>array(
                                       "class"=>"search-button",
                                       "data-toggle"=>"tooltip",
                                       "title"=>Yii::t("' . $this->messageCatalogStandard . '","Advanced Search"),
                                   )
                           )
                       );
                    ?>
                    <?php
                $this->widget(
                       "bootstrap.widgets.TbButton",
                       array(
                           #"label"=>Yii::t("' . $this->messageCatalogStandard . '","Clear"),
                                   "icon"=>"icon-remove-sign",
                                   "size"=>"large",
                                   "url"=>Yii::app()->baseURL."/".Yii::app()->request->getPathInfo(),
                                   "htmlOptions"=>array(
                                      "data-toggle"=>"tooltip",
                                      "title"=>Yii::t("' . $this->messageCatalogStandard . '","Clear Search"),
                                   )
                           )
                       );
                    ?>
                    '; ?>
        </div>
        <?= "<?php endif; ?>" ?>

    </div>


</div>


<?= "<?php if(\$this->action->id == 'admin'): ?>" ?>
<div class="search-form" style="display:none">
    <?= "<?php Yii::beginProfile('{$this->modelClass}.view.toolbar.search'); ?>"; ?>
    <?=
    "<?php \$this->renderPartial('_search',array('model' => \$model,)); ?>\n"; ?>
    <?= "<?php Yii::endProfile('{$this->modelClass}.view.toolbar.search'); ?>"; ?>
</div>
<?= "<?php endif; ?>" ?>

<?= "<?php Yii::endProfile('{$this->modelClass}.view.toolbar'); ?>"; ?>
