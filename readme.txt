This is the (not yet) official template collection for the Gii System.

To use it, simply extract the content of the archive into your application 
extensions/ directory and configure the templates in the 'generatorPaths'
section of the gii Configuration inside your application configuration:

			'gii'=>array(
				'class'=>'system.gii.GiiModule',
				'password'=>'<your gii password>',
				'generatorPaths'=>array(
					'ext.gtc',   // Gii Template Collection
					),
				),

After that, two new Generators 'FullCrud' and 'FullModel' should be available
in your Gii index page.

Please note that the provided CAdvancedArBehavior and the Relation widget will
be inserted into your application Configuration automatically after the first
use of the Generator.

De, fr, lt, es and pt translations are provided in the vendors/messages 
directory. To make your Application use them, simply copy them over to the
messages directory of your Web Application.

Please enjoy this extension and give your feedback at the Google Code 
Repository, thank you. Of course, any templates are appreciated and just leave
a comment or mail thyseus@gmail.com
