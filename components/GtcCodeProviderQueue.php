<?php

/**
 * Class to handle the method calls
 * Class GtcProvider
 */
class GtcCodeProviderQueue
{

    public $providers = array();
    public $codeModel;

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
                    #echo 'Provider: '.$class."\n";
                    return $c;
                }
            }
        }
    }
}