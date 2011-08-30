<?php
include(dirname(__FILE__).'/copyright.php');
?>
class <?php echo ucfirst($this->className); ?> extends <?php echo $this->baseClass."\n"; ?>
{
    function __construct() {
        // parent::__construct();
   }

   function __destruct() {
       //your code here...
   }
}