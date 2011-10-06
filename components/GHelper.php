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

	public static function resolveController($relation) {
		$model = new $relation[1];
		$reflection = new ReflectionClass($model);
		$module = preg_match("/\/modules\/([a-zA-Z0-9]+)\//", $reflection->getFileName(), $matches);
		$modulePrefix = (isset($matches[$module]))?"/".$matches[$module]."/":"/";
		$controller = $modulePrefix.strtolower(substr($relation[1],0,1)).substr($relation[1],1);
		return $controller;
	}

}

?>
