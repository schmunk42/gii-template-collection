<div class="info" style="border: 1px solid;padding: 5px; margin:5px;">
    <h2> Dependencies </h2>

    <p>The generated code depends on third party libraries. Ensure that you have the following in your
        composer.json: </p>

    <pre>
    "repositories":[
        {
            "type": "package",
            "package": {
                "name": "clevertech/yiibooster",
                "version": "dev-master",
                "source": {
                        "url": "https://github.com/motin/YiiBooster.git",
                    "type": "git",
                "reference": "ed6453edd0dc92b5044ee923d71c9e0742b8723f"
                }
            }
        },
        {
            "type":"composer",
            "url":"http://packages.phundament.com"
        },
        ...
    ],
    "require":{
        "php":">=5.3.2",
        "yiisoft/yii":"1.1.13",
        "vitalets/x-editable-yii": "1.2.0",
        "clevertech/YiiBooster": "dev-master",
        ...
    },
    </pre>

    <br/>

</div>
