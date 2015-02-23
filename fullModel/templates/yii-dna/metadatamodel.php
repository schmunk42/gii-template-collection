<?php echo "<?php\n"; ?>

// auto-loading
Yii::setPathOfAlias('<?php echo 'Metadata' . $modelClass; ?>', dirname(__FILE__));
Yii::import('<?php echo 'Metadata' . $modelClass; ?>.*');

class <?php echo 'Metadata' . $modelClass; ?> extends <?php echo 'Base' . $modelClass."\n"; ?>
{
<?php
if (!empty($this->metadataClassTraits)) {
    echo "\n";
    foreach(explode(",", $this->metadataClassTraits) as $traitName) {
        echo '    use '.$traitName.';' . "\n";
    }
    echo "\n";
}
?>
}
