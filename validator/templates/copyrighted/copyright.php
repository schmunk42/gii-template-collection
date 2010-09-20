<?php echo '<?php'."\n"; ?>
/**
 * <?php echo $this->className; ?> class file.
 *
<?php
$author_email = ($this->p('author-email'))
    ? ' <'.$this->p('author-email').">\n"
    : "\n";;
if ($this->p('author')) echo ' * @author '.$this->p('author').$author_email;
if ($this->p('link')) echo ' * @link '.$this->p('link')."\n";
if ($this->p('copyright')) echo ' * @copyright Copyright &copy; '.$this->p('copyright')."\n";
if ($this->p('license')) echo ' * @license '.$this->p('license')."\n";
?>
 */

/**
<?php echo $this->renderCommentPart(); ?>
 *
<?php
if ($this->p('author')) echo ' * @author '.$this->p('author').$author_email;
if ($this->p('version'))echo ' * @version '.$this->p('version')."\n";
if ($this->p('package')) echo ' * @package '.$this->p('package')."\n";
if ($this->p('since')) echo ' * @since '.$this->p('since')."\n";
?>
 */

