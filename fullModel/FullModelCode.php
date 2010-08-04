<?php
Yii::import('system.gii.generators.model.ModelCode');

class FullModelCode extends ModelCode
{
	public function prepare()
	{
		parent::prepare();

		// Make sure that the CSaveRelationsBehavior is in the application
		// components folder. Ff it is not, copy it over there.

		$extPath = Yii::getPathOfAlias('ext');
		if($extPath===false)
			mkdir($extPath);

		if(!is_dir($extPath) || !is_writable($extPath))
			throw new CException ("Fatal Error: Your application extensions/ is not a writable directory!");	

		$fileNames = scandir($extPath);
		if(!in_array('CSaveRelationsBehavior.php', $fileNames)) 
		{
			$gtcPath = Yii::getPathOfAlias('ext.gtc.vendors.CSaveRelationsBehavior');
			if(!copy($gtcPath.'/CSaveRelationsBehavior.php', $extPath.'/CSaveRelationsBehavior.php'))
				throw new CException('CSaveRelationsBehavior.php could not be copied over to your extensions/ directory.');
		}

	}
 protected function generateRelations()
  {
    $relations=array();
	$i = 0;
    foreach(Yii::app()->db->schema->getTables() as $table)
    {
      if($this->tablePrefix!='' && strpos($table->name,$this->tablePrefix)!==0)
        continue;
      $tableName=$table->name;

      if ($this->isRelationTable($table))
      {
        $pks=$table->primaryKey;
        $fks=$table->foreignKeys;

        $table0=$fks[$pks[1]][0];
        $table1=$fks[$pks[0]][0];
        $className0=$this->generateClassName($table0);
        $className1=$this->generateClassName($table1);

        $unprefixedTableName=$this->removePrefix($tableName);

				$relationName=$this->generateRelationName($table0, $table1, true);
				if(!isset($relations[$className0][$relationName]))
					$relations[$className0][$relationName]="array(self::MANY_MANY, '$className1', '$unprefixedTableName($pks[1], $pks[0])')";

				$relationName=$this->generateRelationName($table1, $table0, true);
				if(!isset($relations[$className0][$relationName]))
					$relations[$className1][$relationName]="array(self::MANY_MANY, '$className0', '$unprefixedTableName($pks[0], $pks[1])')";
			}
      else
      {
        $className=$this->generateClassName($tableName);
        foreach ($table->foreignKeys as $fkName => $fkEntry)
        {
          // Put table and key name in variables for easier reading
          $refTable=$fkEntry[0]; // Table name that current fk references to
          $refKey=$fkEntry[1];   // Key in that table being referenced
          $refClassName=$this->generateClassName($refTable);

          // Add relation for this table
          $relationName=$this->generateRelationName($tableName, $fkName, false);
          $relations[$className][$relationName]="array(self::BELONGS_TO, '$refClassName', '$fkName')";

          // Add relation for the referenced table
          $relationType=$table->primaryKey === $fkName ? 'HAS_ONE' : 'HAS_MANY';
          $relationName=$this->generateRelationName($refTable, $this->removePrefix($tableName,false), $relationType==='HAS_MANY');
          $relations[$refClassName][$relationName]="array(self::$relationType, '$className', '$fkName')";
        }
      }
    }
    return $relations;
  }


}
?>
