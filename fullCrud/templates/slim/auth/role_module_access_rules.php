public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'expression' => 'Role::checkAccess()',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
