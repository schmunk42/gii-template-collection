<?php echo "<?php\n"; ?>
<?php echo $this->renderComment(); ?>
class <?php echo $this->buildClassName(); ?> extends <?php echo $this->baseClass."\n"; ?>
{
<?php $this->printSubTemplate(); ?>
}