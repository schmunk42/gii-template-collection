<div class="btn-toolbar">
    <div class="btn-group">
        <?php
        echo '<?php echo CHtml::link("Manage",array("admin"),array("class"=>"btn")); ?>';
        echo '<?php
            switch($this->action->id) {
                case "admin":
                    echo CHtml::link("Create",array("create"),array("class"=>"btn"));
                    break;
                case "view":
                    echo CHtml::link("Update",array("update","id"=>$model->id),array("class"=>"btn"));
                    echo CHtml::link("Delete", "#",array("submit"=>array("delete","id"=>$model->id), "class"=>"btn btn-danger","confirm"=>"Do you want to delete this item?"));
                    break;
                case "update":
                    echo CHtml::link("View",array("view","id"=>$model->id),array("class"=>"btn"));
                    echo CHtml::link("Delete", "#",array("submit"=>array("delete","id"=>$model->id), "class"=>"btn btn-danger","confirm"=>"Do you want to delete this item?"));
                    break;
            }
        ?>';
        ?>
    </div>
    <?php echo "<?php if(\$this->action->id == 'admin'): ?>" ?>
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            Relations
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php
// render relation links
            $model = new $this->modelClass;
            #echo "<div class='btn-toolbar'>";
            foreach ($model->relations() AS $key => $relation) {
                echo "<li>";
                echo '<?php echo CHtml::link(
        Yii::t("app", "' . $relation[1] . '"),
        array("' . $this->codeProvider->resolveController($relation) . '/admin")) ?>';
                echo " </li>\n";
                #Yii::t("app", substr(str_replace("Relation", "", $relation[0]), 1)) . " " .
            }
            #echo "</div>";
            ?>
        </ul>
    </div>
    <div class="btn-group">
        <?php echo "<?php echo CHtml::link(Yii::t('app', 'Advanced Search'),'#',array('class'=>'btn search-button')); ?>"; ?>
    </div>
    <?php echo "<?php endif; ?>" ?>
</div>

<div class="search-form" style="display:none">
    <?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div>