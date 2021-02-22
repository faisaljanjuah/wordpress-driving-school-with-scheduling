<?php

    function validate($type, $val){

        $response = 'true';

        if($type == 'email'){
            if(!(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $val))) {
                $response = 'false';
            }
        }
        elseif($type == 'alphanum'){
            if(!(preg_match("/^[a-zA-Z0-9 '-._]+$/i", $val))) {
                $response = 'false';
            }
        }
        elseif($type == 'alphanumbr'){
            if(!(preg_match("/^[a-zA-Z0-9 <>\'-._]+$/i", $val))) {
                $response = 'false';
            }
        }
        elseif($type == 'date'){
            if(!(preg_match("/^[0-9-]+$/i", $val))) {
                $response = 'false';
            }
        }
        else {
            $response = 'false';
        }
        return $response;

    }