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
'roles' => array('*'),
),
array(
'allow',
'actions' => array('getOptions', 'create', 'update'),
'roles' => array('UserCreator'),
),
array(
'allow',
'actions' => array('admin', 'delete'),
'users' => array('admin'),
),
array(
'deny',
'users' => array('*'),
),
);
}
