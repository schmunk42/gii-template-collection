<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	public $layout='//layouts/main';

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
					printf("\t\t\tif(isset(\$_POST['%s']['%s']))\n", $this->modelClass, $relation[1]);
					printf("\t\t\t\t\$model->setRelationRecords('%s', \$_POST['%s']['%s']);\n", $key, $this->modelClass, $relation[1]);
				}
			}
?>

			if($model->save()) {

			$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
				}
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
					printf("\t\t\tif(isset(\$_POST['%s']['%s']))\n", $this->modelClass, $relation[1]);
					printf("\t\t\t\t\$model->setRelationRecords('%s', \$_POST['%s']['%s']);\n", $key, $this->modelClass, $relation[1]);
				}
			}
?>

			if($model->save()) {

      $this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
			}
		}

		$this->render('update',array(
					'model'=>$model,
					));
	}

	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadModel()->delete();

			if(!isset($_GET['ajax']))
			{
				if(isset($_POST['returnUrl']))
					$this->redirect($_POST['returnUrl']); 
				else
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
			throw new CHttpException(404,'The requested page does not exist.');
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
