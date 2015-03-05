<?php echo "<?php";?>

<?=" 
class {$migrate_class_name} extends CDbMigration
{

    public function up()
    {
        \$this->execute(\"
            INSERT INTO `AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.*','0','{$rightsPrefix}',NULL,'N;');
            INSERT INTO `AuthItem` VALUES('{$rightsPrefix}Edit', 2, '{$rightsPrefix} edit', NULL, 'N;');
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}Edit', '{$rightsPrefix}.*');
                
        \");
    }

    public function down()
    {
        \$this->execute(\"
            DELETE FROM `authitemchild` WHERE `parent` = '{$rightsPrefix}Edit';
            DELETE FROM `AuthItem` WHERE `name` = '{$rightsPrefix}.*';
            DELETE FROM `AuthItem` WHERE `name` = '{$rightsPrefix}Edit';                
        \");
    }

    public function safeUp()
    {
        \$this->up();
    }

    public function safeDown()
    {
        \$this->down();
    }
}


";?>

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
'actions' => array('create', 'editableSaver', 'update', 'delete', 'admin', 'view','ajaxCreate'),
'roles' => array('<?php echo $rightsPrefix ?>.*'),
),
array(
'deny',
'users' => array('*'),
),
);
}
