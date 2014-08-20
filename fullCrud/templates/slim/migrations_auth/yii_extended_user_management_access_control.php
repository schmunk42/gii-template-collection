<?php echo "<?php";?>

<?=" 
class {$migrate_class_name} extends CDbMigration
{

    public function up()
    {
        \$this->execute(\"
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.*','0','{$rightsPrefix}',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.edit','0','{$rightsPrefix} module edit',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.fullcontrol','0','{$rightsPrefix} module full control',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('{$rightsPrefix}.readonly','0','{$rightsPrefix} module readonly',NULL,'N;');
                
            INSERT INTO `authitem` VALUES('{$rightsPrefix}Edit', 2, '{$rightsPrefix} edit', NULL, 'N;');
            INSERT INTO `authitem` VALUES('{$rightsPrefix}View', 2, '{$rightsPrefix} view', NULL, 'N;');
            
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}Edit', '{$rightsPrefix}.*');
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}Edit', '{$rightsPrefix}.edit');
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}Edit', '{$rightsPrefix}.fullcontrol');
            INSERT INTO `authitemchild` VALUES('{$rightsPrefix}View', '{$rightsPrefix}.readonly');

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