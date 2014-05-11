public function filters()
{
return array(
'accessControl',
);
}

public function accessRules()
{
return array(
array(
'allow',
'actions' => array('index', 'view'),
'users' => array('*'),
),
array(
'allow',
'actions' => array('create', 'update','ajaxCreate'),
'users' => array('@'),
),
array(
'allow',
'actions' => array('admin', 'delete','ajaxCreate'),
'users' => array('admin'),
),
array(
'deny',
'users' => array('*'),
),
);
}
