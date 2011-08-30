<?php echo "<?php\n"; ?>
<?php echo $this->renderComment(); ?>
class <?php echo ucfirst($this->className); ?> extends <?php echo $this->baseClass."\n"; ?>
{
    function __construct() {
        // parent::__construct();
   }

   function __destruct() {
       //your code here...
   }

}