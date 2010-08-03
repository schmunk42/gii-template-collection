<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	public $layout='//layouts/column2';
	private $_model;

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

	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	public function actionCreate()
	{
		$model=new <?php echo $this->modelClass; ?>;

		foreach($_POST as $key => $value) {
			if(is_array($value))
				$_SESSION[$key] = $value;
		}

		if(isset($_SESSION['<?php echo $this->modelClass; ?>'])) 
			$model->attributes = $_SESSION['<?php echo $this->modelClass; ?>'];

		$this->performAjaxValidation($model);

		$<?php echo $this->modelClass; ?>Data = Yii::app()->request->getPost('<?php echo $this->modelClass; ?>');
		if($<?php echo $this->modelClass; ?>Data !== null)
		{
			$model->attributes = $<?php echo $this->modelClass; ?>Data;

<?php
			// Add additional MANY_MANY Attributes to the model object
			foreach(CActiveRecord::model($this->model)->relations() as $key => $relation)
			{
				if($relation[0] == 'CManyManyRelation')
				{
					printf("\t\t\tif(isset(\$%sData['%s']))\n", $this->modelClass, $relation[1]);
					printf("\t\t\t\t\$model->%s = \$%sData['%s'];\n", $key, $this->modelClass, $relation[1]);
				}
			}
?>

	if($model->save()) {
		unset($_SESSION['<?php echo $this->modelClass; ?>']);
		if(isset($_POST['returnUrl']))
			$this->redirect($_POST['returnUrl']); 
		else
			$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
	}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate()
	{
		$model=$this->loadModel();

		$this->performAjaxValidation($model);

		$<?php echo $this->modelClass; ?>Data = Yii::app()->request->getPost('<?php echo $this->modelClass; ?>');
		if($<?php echo $this->modelClass; ?>Data !== null)
		{
			$model->attributes = $<?php echo $this->modelClass; ?>Data;

<?php
			// Add additional MANY_MANY Attributes to the model object
			foreach(CActiveRecord::model($this->model)->relations() as $key => $relation)
			{
				if($relation[0] == 'CManyManyRelation')
				{
					printf("\t\t\tif(isset(\$%sData['%s']))\n", $this->modelClass, $relation[1]);
					printf("\t\t\t\t\$model->%s = \$%sData['%s'];\n", $key, $this->modelClass, $relation[1]);
				}
			}
?>

			if($model->save())
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
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

			if(Yii::app()->request->getQuery('ajax') === null)
			{
				$returnUrl = Yii::app()->request->getPost('returnUrl');
				$this->redirect(!empty($returnUrl) ? $returnUrl : array('admin'));
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

		$<?php echo $this->modelClass; ?>Data = Yii::app()->request->getQuery('<?php echo $this->modelClass; ?>');
		if($<?php echo $this->modelClass; ?>Data !== null)
			$model->attributes = $<?php echo $this->modelClass; ?>Data;

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel()
	{
		if($this->_model===null)
		{
			$id = Yii::app()->request->getQuery('id');
			if(!empty($id))
				$this->_model = <?php echo $this->modelClass; ?>::model()->findbyPk($id);

			if($this->_model===null)
				throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		}
		return $this->_model;
	}

	protected function performAjaxValidation($model)
	{
		$ajax = Yii::app()->request->getPost('ajax'); 
		if($ajax == '<?php echo $this->class2id($this->modelClass); ?>-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
