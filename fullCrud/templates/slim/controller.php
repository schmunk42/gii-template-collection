<?php echo "<?php\n"; ?>

<?php $pk = CActiveRecord::model($this->modelClass)->tableSchema->primaryKey ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
    #public $layout='//layouts/column2';

    public $defaultAction = "admin";
    public $scenario = "crud";
    public $scope = "crud";

<?php
    $authPath = 'gtc.fullCrud.templates.slim.auth.';
    Yii::app()->controller->renderPartial($authPath . $this->authTemplateSlim, array('rightsPrefix'=>$this->getRightsPrefix()));
    ?>

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        if ($this->module !== null) {
            $this->breadcrumbs[$this->module->Id] = array('/' . $this->module->Id);
        }
        return true;
    }

    public function actionView($<?= $pk ?>)
    {
        $model = $this->loadModel($<?= $pk ?>);
        $this->render('view', array('model' => $model,));
    }

    public function actionCreate()
    {
        $model = new <?php echo $this->modelClass; ?>;
        $model->scenario = $this->scenario;

        <?php if($this->validation == 1 || $this->validation == 3) { ?>$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
<?php } ?>

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];

<?php
            // Add additional MANY_MANY Attributes to the model object
            foreach(CActiveRecord::model($this->modelClass)->relations() as $key => $relation)
            {
                if($relation[0] == 'CManyManyRelation')
                {
                    printf("            if(isset(\$_POST['%s']['%s']))\n", $this->modelClass, $relation[1]);
                    printf("                \$model->setRelationRecords('%s', \$_POST['%s']['%s']);\n", $key, $this->modelClass, $relation[1]);
                }
            }
?>
            try {
                if ($model->save()) {
                    if (isset($_GET['returnUrl'])) {
                        $this->redirect($_GET['returnUrl']);
                    } else {
                        $this->redirect(array('view', '<?= $pk ?>' => $model-><?= $pk ?>));
                    }
                }
            } catch (Exception $e) {
                $model->addError('<?= $pk ?>', $e->getMessage());
            }
        } elseif (isset($_GET['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
        }

        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($<?= $pk ?>)
    {
        $model = $this->loadModel($<?= $pk ?>);
        $model->scenario = $this->scenario;

        <?php if($this->validation == 1 || $this->validation == 3) { ?>$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
<?php } ?>

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];

<?php
        foreach(CActiveRecord::model($this->modelClass)->relations() as $key => $relation)
            {
                if($relation[0] == 'CManyManyRelation')
                {
                    printf("            if(isset(\$_POST['%s']['%s']))\n", $this->modelClass, $relation[1]);
                    printf("                \$model->setRelationRecords('%s', \$_POST['%s']['%s']);\n", $key, $this->modelClass, $relation[1]);
                    echo "else\n";
                    echo "\$model->setRelationRecords('{$key}',array());\n";
                }
            }
?>

            try {
                if ($model->save()) {
                    if (isset($_GET['returnUrl'])) {
                        $this->redirect($_GET['returnUrl']);
                    } else {
                        $this->redirect(array('view', '<?= $pk ?>' => $model-><?= $pk ?>));
                    }
                }
            } catch (Exception $e) {
                $model->addError('<?= $pk ?>', $e->getMessage());
            }
        }

        $this->render('update', array('model' => $model,));
    }

    public function actionEditableSaver()
    {
        Yii::import('EditableSaver'); //or you can add import 'ext.editable.*' to config
        $es = new EditableSaver('<?php echo $this->modelClass; ?>'); // classname of model to be updated
        $es->update();
    }

    public function actionDelete($<?= $pk ?>)
    {
        if (Yii::app()->request->isPostRequest) {
            try {
                $this->loadModel($<?= $pk ?>)->delete();
            } catch (Exception $e) {
                throw new CHttpException(500, $e->getMessage());
            }

            if (!isset($_GET['ajax'])) {
                if (isset($_GET['returnUrl'])) {
                    $this->redirect($_GET['returnUrl']);
                } else {
                    $this->redirect(array('admin'));
                }
            }
        } else {
            throw new CHttpException(400, Yii::t('<?php echo $this->messageCatalog; ?>', 'Invalid request. Please do not repeat this request again.'));
        }
    }

    public function actionAdmin()
    {
        $model = new <?php echo $this->modelClass; ?>('search');
        $scopes = $model->scopes();
        if (isset($scopes[$this->scope])) {
            $model->{$this->scope}();
        }
        $model->unsetAttributes();

        if (isset($_GET['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
        }

        $this->render('admin', array('model' => $model,));
    }

    public function loadModel($id)
    {
        $m = <?php echo $this->modelClass; ?>::model();
        // apply scope, if available
        $scopes = $m->scopes();
        if (isset($scopes[$this->scope])) {
            $m->{$this->scope}();
        }
        $model = $m->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, Yii::t('<?php echo $this->messageCatalog; ?>', 'The requested page does not exist.'));
        }
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === '<?php echo $this->class2id($this->modelClass); ?>-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
