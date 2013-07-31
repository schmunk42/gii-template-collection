<div class="info" style="border: 1px solid;padding: 5px; margin:5px;">
    <h2> Url Configuration </h2>

    <p> Use this url configuration params in your CUrlManager route configuration
        to allow easy acccess to your new C-R-U-D views: </p>

    <code>
        'rules' => array(<br/>
        '<?php echo $model->controller; ?>' => '<?php echo $model->controller; ?>/index', <br/>
        '<?php echo $model->controller; ?>/admin' => '<?php echo $model->controller; ?>/admin', <br/>
        ),
    </code>

    <br/>

</div>
