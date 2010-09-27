<?php

/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Description ...
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id$
 * @package pii.cells
 * @since 2.0
 */
class GHelper {

	public static function guessNameColumn($columns) {
		$name = Yii::t('app', 'name');
		$title = Yii::t('app', 'title');


		foreach ($columns as $column) {
			if (!strcasecmp($column->name, $name))
				return $column->name;
		}
		foreach ($columns as $column) {
			if (!strcasecmp($column->name, $title))
				return $column->name;
		}


		foreach ($columns as $column) {
			if (stripos($column->name, $name) !== false)
				return $column->name;
		}
		foreach ($columns as $column) {
			if (stripos($column->name, $title) !== false)
				return $column->name;
		}


		foreach ($columns as $column) {
			if ($column->isPrimaryKey)
				return $column->name;
		}
		return 'id';
	}

	public static function resolveController($relation) {
		return (string)(strtolower(substr($relation[1],0,1)).substr($relation[1],1));
	}

}

?>
