<?php echo "<?php\n"; ?>
<?php echo $this->renderComment(); ?>
class <?php echo ucfirst($this->className)."Filter"; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	/**
	 * Performs the pre-action filtering.
	 * @param CFilterChain the filter chain that the filter is on.
	 * @return boolean whether the filtering process should continue and the action
	 * should be executed.
	 */
	protected function preFilter($filterChain)
	{
		return true;
	}

	/**
	 * Performs the post-action filtering.
	 * @param CFilterChain the filter chain that the filter is on.
	 */
	protected function postFilter($filterChain)
	{
	}
}