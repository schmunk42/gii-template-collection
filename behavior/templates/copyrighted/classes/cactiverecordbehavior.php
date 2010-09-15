
	/**
	 * Responds to {@link CActiveRecord::onBeforeSave} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * You may set {@link CModelEvent::isValid} to be false to quit the saving process.
	 * @param CModelEvent event parameter
	 */
	public function beforeSave($event)
	{
	    // your code here...
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterSave} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CModelEvent event parameter
	 */
	public function afterSave($event)
	{
	    // your code here...
	}

	/**
	 * Responds to {@link CActiveRecord::onBeforeDelete} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * You may set {@link CModelEvent::isValid} to be false to quit the deletion process.
	 * @param CEvent event parameter
	 */
	public function beforeDelete($event)
	{
	    // your code here...
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterDelete} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CEvent event parameter
	 */
	public function afterDelete($event)
	{
	    // your code here...
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterConstruct} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CEvent event parameter
	 */
	public function afterConstruct($event)
	{
	    // your code here...
	}

	/**
	 * Responds to {@link CActiveRecord::onBeforeFind} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CEvent event parameter
	 */
	public function beforeFind($event)
	{
	    // your code here...
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterFind} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CEvent event parameter
	 */
	public function afterFind($event)
	{
	    // your code here...	    
	}

