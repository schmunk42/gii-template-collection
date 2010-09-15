<?php echo "<?php\n"; ?>
<?php echo $this->renderComment(); ?>
class <?php echo ucfirst($this->className); ?> extends <?php echo $this->baseClass."\n"; ?>
{
	/**
	 * Renders the content of the portlet.
	 */
	protected function renderContent()
	{
	}
}