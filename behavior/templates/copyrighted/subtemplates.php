<?php
include(dirname(__FILE__).'/copyright.php');
?>
class <?php echo $this->buildClassName(); ?> extends <?php echo $this->baseClass."\n"; ?>
{
<?php $this->printSubTemplate(); ?>
}