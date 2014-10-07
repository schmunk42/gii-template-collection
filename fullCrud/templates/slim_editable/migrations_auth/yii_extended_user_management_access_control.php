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
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.Menu','0','{$rightsPrefix} show menu',NULL,'N;');
                

        \");
    }

    public function down()
    {
        \$this->execute(\"
            DELETE FROM `authitem` WHERE `name`= '{$rightsPrefix}.*';
            DELETE FROM `authitem` WHERE `name`= '{$rightsPrefix}.Create';
            DELETE FROM `authitem` WHERE `name`= '{$rightsPrefix}.View';
            DELETE FROM `authitem` WHERE `name`= '{$rightsPrefix}.Update';
            DELETE FROM `authitem` WHERE `name`= '{$rightsPrefix}.Delete';
            DELETE FROM `authitem` WHERE `name`= '{$rightsPrefix}.Menu';

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