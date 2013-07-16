<?php
/**
 * Global shorthands
 */

function php($code = null){
    if ($code === null) {
        echo "<?php ";
    } else {
        echo "<?php ".$code." ?>";
    }
}

function endphp(){
    echo " ?>";
}


/**
 * Class to provide code snippets for CRUD generation
 */
class FullCrudHelper
{

    static public function resolveController($relation)
    {
        return strtolower(substr($relation[1], 0, 1)) . substr($relation[1], 1);
    }

    static public function generateRelationHeader($model, $relationName, $relationInfo)
    {
        $controller = self::resolveController($relationInfo); // TODO
        $code = "";
        $code .= "
    \$this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array(
                'label'=>'".ucfirst($relationName)."',
                'icon'=>'icon-list-alt',
                'url'=> array('{$controller}/admin')
            ),
            array(
                'icon'=>'icon-plus',
                'url'=>array(
                    '{$controller}/create',
                    '{$relationInfo[1]}' => array('{$relationInfo[2]}'=>\$model->{\$model->tableSchema->primaryKey})
                    )
                ),
            ),
        )
    );";

        return $code;
    }

    static public function generateRelation($model, $relationName, $relationInfo, $captureOutput = false)
    {
        $relatedModel = CActiveRecord::model($relationInfo[1]);
        if ($columns = $relatedModel->tableSchema->columns) {

            $suggestedfield = self::suggestIdentifier($relatedModel);
            $field          = current($columns);
            $style          = $relationInfo[0] == 'CManyManyRelation' ? 'multiselect' : 'dropdownlist';

            if (is_object($field)) {
                if ($relationInfo[0] == 'CHasOneRelation') {
                    return "if (\$model->{$relationName} !== null) echo \$model->{$relationName}->{$suggestedfield};";
                }

                // we always allow empty, so the does not accidentally select the first value
                $allowEmpty = true;

                return ("\$this->widget(
					'Relation',
					array(
							'model' => \$model,
							'relation' => '{$relationName}',
							'fields' => '{$suggestedfield}',
							'allowEmpty' => {$allowEmpty},
							'style' => '{$style}',
							'htmlOptions' => array(
								'checkAll' => 'all'),
							)
						" . ($captureOutput ? ", true" : "") . ")");
            }
        }
    }

    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    static public function generateValueField($modelClass, $column, $view = false)
    {
        if ($column->isForeignKey) {

            $model = CActiveRecord::model($modelClass);
            $table = $model->getTableSchema();
            $fk    = $table->foreignKeys[$column->name];

            // We have to look into relations to find the correct model class (i.e. if models are generated with table prefix)
            // TODO: do not repeat yourself (foreach) - this is a hotfix
            foreach ($model->relations() as $key => $value) {
                if (strcasecmp($value[2], $column->name) == 0) {
                    $relation = $value;
                }
            }

            if (!isset($relation)) {
                return "'" . $column->name . "'";
            }

            $relatedModel     = CActiveRecord::model($relation[1]);
            $relatedModelName = $relation[1];
            $fcolumns   = $relatedModel->attributeNames();

            //$rel = $model->getActiveRelation($column->name);
            $relname = strtolower($fk[0]);
            foreach ($model->relations() as $key => $value) {
                if (strcasecmp($value[2], $column->name) == 0) {
                    $relname = $key;
                }
            }
            //return("\$model->{$relname}->{$fcolumns[1]}");
            //return("CHtml::value(\$model,\"{$relname}.{$fcolumns[1]}\")");
            //return("{$relname}.{$fcolumns[1]}");
            if ($view === true) {
                return "array(
					'name'=>'{$column->name}',
					'value'=>CHtml::value(\$model,'{$relname}.".self::suggestIdentifier($relatedModel)."'),
					)";
            } elseif ($view == 'search') {
                return "echo \$form->dropDownList(\$model,'{$column->name}',CHtml::listData({$relatedModelName}::model()->findAll(),
                '{$fcolumns[0]}', '{$fcolumns[1]}'),array('prompt'=>'all'))";
            } else {
                return "array(
					'name'=>'{$column->name}',
					'value'=>'CHtml::value(\$data,\\'{$relname}.".self::suggestIdentifier($relatedModel)."\\')',
							'filter'=>CHtml::listData({$relatedModelName}::model()->findAll(), '{$fcolumns[0]}', '".self::suggestIdentifier($relatedModel)."'),
							)";
            }
            //{$relname}.{$fcolumns[1]}
        } else {
            /*if (strtoupper($column->dbType) == 'BOOLEAN'
                or strtoupper($column->dbType) == 'TINYINT(1)' or
                strtoupper($column->dbType) == 'BIT'
            ) {
                if ($view) {
                    return "array(
					'name'=>'{$column->name}',
					'value'=>\$model->{$column->name}?'yes':'no',
					)";
                }
                else {
                    return "array(
					'name'=>'{$column->name}',
					'value'=>'\$data->{$column->name}?'yes':'no',
							'filter'=>array('0'=>'no','1'=>'yes'),
							)";
                }
            }
            else {*/
            if ($column->name == 'createtime'
                or $column->name == 'updatetime'
                or $column->name == 'timestamp'
            ) {
                return "array(
				'name'=>'{$column->name}',
				'value' =>'date(\"Y. m. d G:i:s\", \$data->{$column->name})')";
            } else {
                return ("'" . $column->name . "'");
            }
            #}
        }
    }

    /**
     * @param CActiveRecord   $modelClass
     * @param CDbColumnSchema $column
     */
    static public function generateEditableField($modelClass, $column, $controller, $view = false)
    {
        if ($column->isForeignKey) {

            return self::generateValueField($modelClass, $column, $view);

        } else {
            return "array(
			'class' => 'editable.EditableColumn',
			'name' => '{$column->name}',
			'editable' => array(
				'url' => \$this->createUrl('{$controller}/editableSaver'),
				'placement' => 'right',
			)
		)";
        }
    }

    // Which column will most probably be the one that gets used to list
    // KEEP THIS CODE it can be called statically
    static public function suggestIdentifier($model)
    {
        if(!$model instanceof CActiveRecord) {
            $model = CActiveRecord::model($model);
        }

        if (is_callable(array($model, 'getItemLabel')))
            return 'itemLabel';

        $nonNumericFound = false;
        $columns = $model->tableSchema->columns;

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
                $fallbackName = $column->name;
                $nonNumericFound = true;
            }
            // Return the first title, name, label column, if found
            if (in_array($column->name, array(
                                             "title",
                                             "name",
                                             "label",
                                        ))) {
                $fallbackName = $column->name;
                break;
            }
        }
        return $fallbackName;
    }

}

?>
