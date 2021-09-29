<?php

if (! function_exists('pr')) {

    function pr(...$vars)
    {
        echo "<pre>"; 

        foreach($vars as $var)
        {
            print_r($var); 
        }

        echo "</pre>";
    }
}

if (! function_exists('prx')) {

    function prx(...$vars)
    {   
        echo "<pre>";

        foreach($vars as $var) {
             print_r($var); 
        }

        echo "</pre>";

        die;
    }
}

if (! function_exists('ine')) {

    function ine(array $haystack, $needle)
    {   
        if(isset($haystack[$needle]) && !empty($haystack[$needle])) {
            return true;
        }
        return false;
    }
}