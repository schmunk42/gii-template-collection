<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
    public $layout='//layouts/column2';

    public function filters()
    {
        return array(
            'accessControl', 
        );
    }    

    public function accessRules()
    {
        return array(
            array('allow',  
                'actions'=>array('index','view'),
                'users'=>array('*'),
            ),
            array('allow', 
                'actions'=>array('create','update'),
                'users'=>array('@'),
            ),
            array('allow', 
                'actions'=>array('admin','delete'),
                'users'=>array('admin'),
            ),
            array('deny',  
                'users'=>array('*'),
            ),
        );
    }
    
    public function beforeAction($action){
        parent::beforeAction($action);
        // map identifcationColumn to id
        if (!isset($_GET['id']) && isset($_GET['<?php echo $this->tableSchema->primaryKey; ?>'])) {
            $model=<?php echo $this->modelClass; ?>::model()->find('<?php echo $this->tableSchema->primaryKey; ?> = :<?php echo $this->tableSchema->primaryKey; ?>', array(
            ':<?php echo $this->tableSchema->primaryKey; ?>' => $_GET['<?php echo $this->tableSchema->primaryKey; ?>']));
            if ($model !== null) {
                $_GET['id'] = $model-><?php echo CActiveRecord::model($this->modelClass)->tableSchema->primaryKey ?>;
            } else {
                throw new CHttpException(400);
            }
        }
        if ($this->module !== null) {
            $this->breadcrumbs[$this->module->Id] = array('/'.$this->module->Id);
        }
        return true;
    }
    
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->render('view',array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = new <?php echo $this->modelClass; ?>;

        <?php if($this->validation == 1 || $this->validation == 3) { ?>
        $this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
    <?php } ?>

        if(isset($_POST['<?php echo $this->modelClass; ?>'])) {
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
                if($model->save()) {
                    if (isset($_GET['returnUrl'])) {
                        $this->redirect($_GET['returnUrl']);
                    } else {
                        $this->redirect(array('view','id'=>$model->id));
                    }
                }
            } catch (Exception $e) {
                $model->addError('<?php echo $this->tableSchema->primaryKey;?>', $e->getMessage());
            }
        } elseif(isset($_GET['<?php echo $this->modelClass; ?>'])) {
                $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
        }

        $this->render('create',array( 'model'=>$model));
    }


    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        <?php if($this->validation == 1 || $this->validation == 3) { ?>
        $this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
        <?php } ?>

        if(isset($_POST['<?php echo $this->modelClass; ?>']))
        {
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
                if($model->save()) {
                    if (isset($_GET['returnUrl'])) {
                        $this->redirect($_GET['returnUrl']);
                    } else {
                        $this->redirect(array('view','id'=>$model->id));
                    }
                }
            } catch (Exception $e) {
                $model->addError('<?php echo $this->tableSchema->primaryKey;?>', $e->getMessage());
            }    
        }

        $this->render('update',array(
                    'model'=>$model,
                    ));
    }

    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            try {
                $this->loadModel($id)->delete();
            } catch (Exception $e) {
                throw new CHttpException(500,$e->getMessage());
            }

            if(!isset($_GET['ajax']))
            {
                    $this->redirect(array('admin'));
            }
        }
        else
            throw new CHttpException(400,
                    Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
    }

    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('<?php echo $this->modelClass; ?>');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    public function actionAdmin()
    {
        $model=new <?php echo $this->modelClass; ?>('search');
        $model->unsetAttributes();

        if(isset($_GET['<?php echo $this->modelClass; ?>']))
            $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo $this->class2id($this->modelClass); ?>-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
