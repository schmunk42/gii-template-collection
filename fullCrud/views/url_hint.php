<div class="info" style="border: 1px solid;padding: 5px; margin:5px;">
<h2> Url Configuration </h2>
<p> Use this url configuration params in your CUrlManager route configuration
to allow easy acccess to your new C-R-U-D views: </p>

<code>
	'rules' => array(<br />
&nbsp;		'<?php echo $model->controller;?>' => '<?php echo $model->controller; ?>/index', <br />
&nbsp;		'<?php echo $model->controller;?>/admin' => '<?php echo $model->controller; ?>/admin', <br />
&nbsp;		'<?php echo $model->controller;?>/&lt;<?php echo $model->identificationColumn; ?>&gt;' => '<?php echo $model->controller; ?>/view', <br />
	),
</code>

<br />

</div>
