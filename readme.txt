This is the (not yet) official template collection for the Gii System.

To use it, simply extract the content of the archive into your application 
extensions/ directory and configure the templates in the 'generatorPaths'
section of the gii Configuration inside your application configuration:

			'gii'=>array(
				'class'=>'system.gii.GiiModule',
				'password'=>'<your gii password>',
				'generatorPaths'=>array(
					'ext.gtc',   // extensions/Gii Template Collection
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
		'ext.gtc.components.*', // Gii Template Collection
		),

-- Experimental --
You also have the ability to add custom field providers by config.
'gii' => array(
    'params' => array(
	'gtc.fullCrud.providers' => array(
	    'p2.gii.fullCrud.providers.P2FieldProvider',
	)
    )
)
(tbd)

De, fr, lt, es, pt and sv translations are provided in the messages/
directory. To make your Application use them, simply copy them over to the
messages directory of your Web Application.

Please enjoy this extension and give your feedback at the Google Code 
Repository, thank you. Of course, any templates are appreciated and just leave
a comment or mail thyseus@gmail.com
