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
'actions' => array('create', 'editableSaver', 'update', 'delete', 'admin', 'view'),
'roles' => array('<?php echo $rightsPrefix ?>.*'),
),
array(
'deny',
'users' => array('*'),
),
);
}
