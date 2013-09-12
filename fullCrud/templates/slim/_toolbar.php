<?php $pk = CActiveRecord::model($this->modelClass)->tableSchema->primaryKey ?>

<div class="btn-toolbar">
    <div class="btn-group">
        <?=
        '<?php
                   switch($this->action->id) {
                       case "create":
                           $this->widget("bootstrap.widgets.TbButton", array(
                               "label"=>Yii::t("' . $this->messageCatalog . '","Manage"),
                        "icon"=>"icon-list-alt",
                        "url"=>array("admin"),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.View")
                    ));
                    break;
                case "admin":
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","Create"),
                        "icon"=>"icon-plus",
                        "url"=>array("create"),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.Create")
                    ));
                    break;
                case "view":
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","Manage"),
                        "icon"=>"icon-list-alt",
                        "url"=>array("admin"),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.View")
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","Update"),
                        "icon"=>"icon-edit",
                        "url"=>array("update","'.$pk .'"=>$model->{$model->tableSchema->primaryKey}),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.Update")
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","Create"),
                        "icon"=>"icon-plus",
                        "url"=>array("create"),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.Create")
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","Delete"),
                        "type"=>"danger",
                        "icon"=>"icon-remove icon-white",
                        "htmlOptions"=> array(
                            "submit"=>array("delete","'.$pk.'"=>$model->{$model->tableSchema->primaryKey}, "returnUrl"=>(Yii::app()->request->getParam("returnUrl"))?Yii::app()->request->getParam("returnUrl"):$this->createUrl("admin")),
                            "confirm"=>Yii::t("' . $this->messageCatalog . '","Do you want to delete this item?")
                        ),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.Delete")
                    ));
                    break;
                case "update":
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","Manage"),
                        "icon"=>"icon-list-alt",
                        "url"=>array("admin"),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.View")
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","View"),
                        "icon"=>"icon-eye-open",
                        "url"=>array("view","'.$pk.'"=>$model->{$model->tableSchema->primaryKey}),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.View")
                    ));
                    $this->widget("bootstrap.widgets.TbButton", array(
                        "label"=>Yii::t("' . $this->messageCatalog . '","Delete"),
                        "type"=>"danger",
                        "icon"=>"icon-remove icon-white",
                        "htmlOptions"=> array(
                            "submit"=>array("delete","'.$pk.'"=>$model->{$model->tableSchema->primaryKey}, "returnUrl"=>(Yii::app()->request->getParam("returnUrl"))?Yii::app()->request->getParam("returnUrl"):$this->createUrl("admin")),
                            "confirm"=>Yii::t("' . $this->messageCatalog . '","Do you want to delete this item?")
                        ),
                        "visible"=>Yii::app()->user->checkAccess("'.$this->getRightsPrefix().'.Delete")
                    ));
                    break;
            }
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
                       "label"=>Yii::t("' . $this->messageCatalog . '","Search"),
                "icon"=>"icon-search",
                "htmlOptions"=>array("class"=>"search-button")
               )
           );
        ?>
        '; ?>
    </div>
    <?= "<?php endif; ?>" ?>

    <?= "<?php if(\$this->action->id == 'admin' || \$this->action->id == 'view'): ?>" ?>
    <?php
    $model = new $this->modelClass;
    if ($model->relations() !== array()):
        ?>
        <div class="btn-group">
            <?=
            "<?php \$this->widget('bootstrap.widgets.TbButtonGroup', array(
                   'buttons'=>array(
                           array('label'=>Yii::t('" . $this->messageCatalog . "','Relations'), 'icon'=>'icon-random', 'items'=>array(";

            // render relation links
            foreach ($model->relations() AS $key => $relation) {
                $replace = array(
                    'CBelongsToRelation' => 'circle-arrow-left',
                    'CManyManyRelation'  => 'resize-horizontal',
                    'CHasManyRelation'   => 'arrow-right',
                    'CHasOneRelation'    => 'circle-arrow-right',
                );
                echo "array('icon'=>'" . strtr($relation[0], $replace) . "','label'=>'" . ucfirst(
                        $key
                    ) . "', 'url' =>array('" . $this->resolveController($relation) . "/admin')),";
            }

            echo "
            )
          ),
        ),
    ));
?>";
            ?>
        </div>

    <?php endif; ?>

    <?= "<?php endif; ?>" ?>
</div>

<?= "<?php if(\$this->action->id == 'admin'): ?>" ?>
<div class="search-form" style="display:none">
    <?=
    "<?php \$this->renderPartial('_search',array('model'=>\$model,)); ?>\n"; ?>
</div>
<?= "<?php endif; ?>" ?>
