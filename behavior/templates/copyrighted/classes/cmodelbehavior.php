
	/**
	 * Responds to {@link CModel::onBeforeValidate} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link owner}.
	 * You may set {@link CModelEvent::isValid} to be false to quit the validation process.
	 * @param CModelEvent event parameter
	 */
	public function beforeValidate($event)
	{
	}

	/**
	 * Responds to {@link CModel::onAfterValidate} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link owner}.
	 * @param CEvent event parameter
	 */
	public function afterValidate($event)
	{
	}

