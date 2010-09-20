<div class="ddeditor">
<button
    type="button"
    id="<?php echo $editorId ?>-editor-bold"><b><?php echo Yii::t('ddeditor','B'); ?></b></button>
<button
    type="button"
    id="<?php echo $editorId ?>-editor-italic"><i><?php echo Yii::t('ddeditor','I'); ?></i></button>
<select id="<?php echo $editorId ?>-editor-h">
    <option value=""><?php echo Yii::t('ddeditor','H'); ?></option>
    <?php for($i=1; $i<=5; $i++ ) : ?>
    <option value="<?php echo $i ?>"><?php echo Yii::t('ddeditor','H'); ?><?php echo $i ?></option>
    <?php endfor; ?>
</select>
<button
    type="button"
    id="<?php echo $editorId ?>-editor-link">URL</button>
<button
    type="button"
    id="<?php echo $editorId ?>-editor-img">IMG</button>
<button
    type="button"
    id="<?php echo $editorId ?>-editor-li">&bull;</button>
<button
    type="button"
    id="<?php echo $editorId ?>-editor-hr"><?php echo Yii::t('ddeditor','HR'); ?></button>
<button
    type="button"
    id="<?php echo $editorId ?>-editor-code">Code</button>
<button
    type="button"
    id="<?php echo $editorId ?>-editor-table"><?php echo Yii::t('ddeditor','Table'); ?></button>
<button
    type="button" 
    id="<?php echo $editorId; ?>-editor-preview"><?php echo Yii::t('ddeditor','Preview'); ?></button>
<br/>
<?php echo CHtml::activeTextArea($model,$attribute,$htmlOptions); ?>
<div id="<?php echo $editorId; ?>-preview" class="preview"><?php echo Yii::t('ddeditor','Loading Preview...'); ?></div>
</div>
