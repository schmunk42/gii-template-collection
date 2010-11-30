<?php

class FullModelGenerator extends CCodeGenerator {
    public $codeModel = 'ext.gtc.fullModel.FullModelCode';

    /**
     * Returns the table names in an array.
     * The array is used to build the autocomplete field.
     * An '*' is appended to the end of the list to allow the generation
     * of models for all tables.
     * @return array the names of all tables in the schema, plus an '*'
     */
    protected function getTables() {
        $tables = Yii::app()->db->schema->tableNames;
        $tables[] = '*';
        return $tables;
    }

}
