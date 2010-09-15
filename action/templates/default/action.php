<?php echo "<?php\n"; ?>
<?php echo $this->renderComment(); ?>
class <?php echo ucfirst($this->className).'Action'; ?> extends <?php echo $this->baseClass."\n"; ?>
{

    public function run()
    {
        // place the action logic here
    }
}