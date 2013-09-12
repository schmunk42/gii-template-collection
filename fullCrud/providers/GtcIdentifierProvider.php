<?php

class GtcIdentifierProvider extends GtcCodeProvider
{

    // Which column will most probably be the one that gets used to list
    // KEEP THIS CODE it can be called statically
    public function suggestIdentifier($model)
    {
        if (!$model instanceof CActiveRecord) {
            $model = CActiveRecord::model(Yii::import($model));
        }

        if (is_callable(array($model, 'getItemLabel'))) {
            return 'itemLabel';
        }

        $nonNumericFound = false;
        $columns         = $model->tableSchema->columns;

        foreach ($columns as $column) {
            if ($column->isPrimaryKey) {
                $fallbackName = $column->name;
            }
            // Use the first non-numeric column as a fallback
            if (!$column->isForeignKey
                && !$column->isPrimaryKey
                && $column->type != 'BIGINT'
                && $column->type != 'INT'
                && $column->type != 'INTEGER'
                && $column->type != 'BOOLEAN'
                && !$nonNumericFound
            ) {
                $fallbackName    = $column->name;
                $nonNumericFound = true;
            }
            // Return the first title, name, label column, if found
            if (in_array(
                $column->name,
                array(
                     "title",
                     "name",
                     "label",
                )
            )
            ) {
                $fallbackName = $column->name;
                break;
            }
        }
        return $fallbackName;
    }
}
