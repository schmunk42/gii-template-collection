<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	public $layout='//layouts/column2';

	<?php 
		$authpath = 'ext.gtc.fullCrud.templates.default.auth.';
	Yii::app()->controller->renderPartial($authpath.$this->authtype);
	?>

		public function actionView()
	{
		$this->render('view',array(
			'model' => $this->loadModel(),
		));
	}

	public function actionCreate()
	{
		$model = new <?php echo $this->modelClass; ?>;

		<?php if($this->enable_ajax_validation) { ?>
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


		if(Yii::app()->request->isAjaxRequest)
			$this->renderPartial('_miniform',array( 'model'=>$model, 'relation' => $relation));
		else
			$this->render('create',array( 'model'=>$model));
	}


	public function actionUpdate()
	{
		$model = $this->loadModel();

		<?php if($this->enable_ajax_validation) { ?>
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

}
