<?=
"
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
            'actions' => array('create', 'admin', 'view', 'update', 'editableSaver', 'delete'),
            'roles' => array('{$rightsPrefix}.*'),
        ),
        array(
            'allow',
            'actions' => array('create'),
            'roles' => array('{$rightsPrefix}.Create'),
        ),
        array(
            'allow',
            'actions' => array('view', 'admin'), // let the user view the grid
            'roles' => array('{$rightsPrefix}.View'),
        ),
        array(
            'allow',
            'actions' => array('update', 'editableSaver'),
            'roles' => array('{$rightsPrefix}.Update'),
        ),
        array(
            'allow',
            'actions' => array('delete'),
            'roles' => array('{$rightsPrefix}.Delete'),
        ),
        array(
            'deny',
            'users' => array('*'),
        ),
    );
}
"?>