<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
    #public $layout='//layouts/column2';

    public $defaultAction = "admin";
    public $scenario = "crud";

<?php
    $authPath = 'gtc.fullCrud.templates.hybrid.auth.';
    Yii::app()->controller->renderPartial($authPath . $this->authTemplateHybrid, array('rightsPrefix'=>$this->getRightsPrefix()));
    ?>

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        // map identifcationColumn to id
        if (!isset($_GET['id']) && isset($_GET['<?php echo $this->tableSchema->primaryKey; ?>'])) {
            $model = <?php echo $this->modelClass; ?>::model()->find(
                '<?php echo $this->tableSchema->primaryKey; ?> = :<?php echo $this->tableSchema->primaryKey; ?>',
                array(
                    ':<?php echo $this->tableSchema->primaryKey; ?>' => $_GET['<?php echo $this->tableSchema->primaryKey; ?>']
                )
            );
            if ($model !== null) {
                $_GET['id'] = $model-><?php echo CActiveRecord::model($this->modelClass)->tableSchema->primaryKey ?>;
            } else {
                throw new CHttpException(400);
            }
        }
        if ($this->module !== null) {
            $this->breadcrumbs[$this->module->Id] = array('/' . $this->module->Id);
        }
        return true;
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);
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
                        $this->redirect(array('view', 'id' => $model-><?php echo CActiveRecord::model($this->modelClass)->tableSchema->primaryKey ?>));
                    }
                }
            } catch (Exception $e) {
                $model->addError('<?php echo $this->tableSchema->primaryKey;?>', $e->getMessage());
            }
        } elseif (isset($_GET['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
        }

        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
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
                        $this->redirect(array('view', 'id' => $model-><?php echo CActiveRecord::model($this->modelClass)->tableSchema->primaryKey ?>));
                    }
                }
            } catch (Exception $e) {
                $model->addError('<?php echo $this->tableSchema->primaryKey;?>', $e->getMessage());
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

    public function actionEditableCreator()
    {
        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model = new <?php echo $this->modelClass; ?>;
            $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
            if ($model->save()) {
                echo CJSON::encode($model->getAttributes());
            } else {
                $errors = array_map(
                    function ($v) {
                        return join(', ', $v);
                    },
                    $model->getErrors()
                );
                echo CJSON::encode(array('errors' => $errors));
            }
        } else {
            throw new CHttpException(400, 'Invalid request');
        }
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            try {
                $this->loadModel($id)->delete();
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

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('<?php echo $this->modelClass; ?>');
        $this->render('index', array('dataProvider' => $dataProvider,));
    }

    public function actionAdmin()
    {
        $model = new <?php echo $this->modelClass; ?>('search');
        $model->unsetAttributes();

        if (isset($_GET['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
        }

        $this->render('admin', array('model' => $model,));
    }

    public function loadModel($id)
    {
        $model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
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

    /**
    * Returns a model used to populate a filterable, searchable
    * and sortable CGridView with the records found by a model relation.
    *
    * Usage:
    * $relatedSearchModel = $this->getRelatedSearchModel($model, 'relationName');
    *
    * Then, when invoking CGridView:
    *    ...
    *        'dataProvider' => $relatedSearchModel->search(),
    *        'filter' => $relatedSearchModel,
    *    ...
    * @returns CActiveRecord
    */
    public function getRelatedSearchModel($model, $name)
    {
        $md = $model->getMetaData();
        if (!isset($md->relations[$name])) {
            throw new CDbException(Yii::t('yii', '{class} does not have relation "{name}".', array('{class}' => get_class($model), '{name}' => $name)));
        }

        $relation = $md->relations[$name];
        if (!($relation instanceof CHasManyRelation)) {
            throw new CException("Currently works with HAS_MANY relations only");
        }

        $className = $relation->className;
        $related = new $className('search');
        $related->unsetAttributes();
        $related->{$relation->foreignKey} = $model->primaryKey;

        if (isset($_GET[$className])) {
            $related->attributes = $_GET[$className];
        }

        return $related;
    }



}
