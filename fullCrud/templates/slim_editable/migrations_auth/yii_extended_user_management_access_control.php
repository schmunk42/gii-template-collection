<?php echo "<?php";?>

<?=" 
class {$migrate_class_name} extends CDbMigration
{

    public function up()
    {
        \$this->execute(\"
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.*','0','{$rightsPrefix}',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.Create','0','{$rightsPrefix} module create',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.View','0','{$rightsPrefix} module view',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.Update','0','{$rightsPrefix} module update',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.Delete','0','{$rightsPrefix} module delete',NULL,'N;');
                
            INSERT INTO `authitem` VALUES('{$rightsPrefix}Create', 2, '{$rightsPrefix} create', NULL, 'N;');
            INSERT INTO `authitem` VALUES('{$rightsPrefix}Update', 2, '{$rightsPrefix} update', NULL, 'N;');
            INSERT INTO `authitem` VALUES('{$rightsPrefix}Delete', 2, '{$rightsPrefix} delete', NULL, 'N;');
            INSERT INTO `authitem` VALUES('{$rightsPrefix}View', 2, '{$rightsPrefix} view', NULL, 'N;');
            
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}Create', '{$rightsPrefix}.Create');
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}Update', '{$rightsPrefix}.Update');
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}Delete', '{$rightsPrefix}.Delete');
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}View', '{$rightsPrefix}.View');

        \");
    }

    public function down()
    {
        \$this->execute(\"
            DELETE FROM `authitemchild` WHERE `parent` = '{$rightsPrefix}Edit';
            DELETE FROM `authitemchild` WHERE `parent` = '{$rightsPrefix}View';

            DELETE FROM `authitem` WHERE `name` = '{$rightsPrefix}.*';
            DELETE FROM `authitem` WHERE `name` = '{$rightsPrefix}.edit';
            DELETE FROM `authitem` WHERE `name` = '{$rightsPrefix}.fullcontrol';
            DELETE FROM `authitem` WHERE `name` = '{$rightsPrefix}.readonly';
            DELETE FROM `authitem` WHERE `name` = '{$rightsPrefix}Edit';
            DELETE FROM `authitem` WHERE `name` = '{$rightsPrefix}View';
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