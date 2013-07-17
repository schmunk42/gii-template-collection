This is the gii template collection
===================================

[Fork on github](https://github.com/schmunk42/gii-template-collection)


Setup
-----

To use it, simply extract the content of the archive into your application 
extensions/ directory and configure the templates in the 'generatorPaths'
section of the gii Configuration inside your application configuration:

    'gii'=>array(
        'class'=>'system.gii.GiiModule',
        'password'=>'<your gii password>',
        'generatorPaths'=>array(
            'path.to.gii-template-collection',   // extensions/Gii Template Collection
            ),
        ),

After that, the new Generators of the Gii Template Collection should be
available in your Gii index page.

The last step is to add 'ext.gtc.components.*' to your import path so
the needed components can be found by the application:

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.user.models.*',
		'path.to.gii-template-collection.components.*', // Relation Widget
	),


Full Model
----------

*tbd*

Full CRUD
---------

### Shorthands

    <?php php() ?>
    $form=$this->beginWidget('CActiveForm', array(
            'action'=>Yii::app()->createUrl($this->route),
            'method'=>'get',
    ));
    <?php endphp() ?>


    <?php php() ?>
    Yii::t('" . $this->messageCatalog . "','Relations')");
    <?php endphp() ?>


    <?php php("echo Yii::t('" . $this->messageCatalog . "','Relations')");") ?>

### Template default

*tbd*

### Template slim

Requires yii-bootstrap and echosen.

### Template hybrid

An enhanced hybrid between the default/slim templates and the bootstrap crud generator.

Requires yii-bootstrap, x-editable-yii and echosen.

#### Experimental

**Note: This feature is not yet available!**

You also have the ability to add custom field providers by config.

    'gii' => array(
        'params' => array(
	        'gtc.fullCrud.providers' => array(
	            'p2.gii.fullCrud.providers.P2FieldProvider',
	        )
        )
    )


Please enjoy this extension and give your feedback at the github
Repository, thank you.

Of course, any templates are appreciated and just leave a comment or mail: schmunk@usrbin.de, fredrik@neam.se or thyseus@gmail.com.


### GTC Coding Standards

* Write PHP code wrapped in short-open-echo tags and double quotes `<?="<?php ...code... ?>"?>`
* Use brackets `{}` to insert variables for generated code

We call this template style **`<?=""?>` Bobsled Coding Style**.


Links
-----

### History

* extension page - http://www.yiiframework.com/extension/gii-template-collection
* up version 0.6 - http://code.google.com/p/gii-template-collection/


