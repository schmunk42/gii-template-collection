<?php

/**
 * Class to handle the method calls
 * Class GtcProvider
 */
class GtcCodeProviderQueue
{

    /**
     * @var array
     */
    public $providers = array();
    /**
     * @var
     */
    public $codeModel;

    /**
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        // walk through providers
        foreach ($this->providers AS $provider) {
            $class = Yii::import($provider);

            if (method_exists($class, $name)) {
                $obj            = new $class;
                $obj->codeModel = $this->codeModel;
                $c              = call_user_func_array(array(&$obj, $name), $args);
                // until a provider returns not null
                if ($c !== null) {
                    Yii::log('Using: '.$class);
                    return $c;
                }
            }
        }
    }
}