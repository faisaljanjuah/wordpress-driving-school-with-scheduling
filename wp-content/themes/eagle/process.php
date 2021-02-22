<?php

    function process_data($form_data){

        // Step 1 "Convert into HTML and remove slashes then encode data"
        $formEncode = htmlentities($form_data); // 1
        $formEncode = stripslashes($formEncode); // 2
        $formEncode = base64_encode($formEncode); // 3


        // // Step 2 "decode data then reconvert into html"
        // $formEncode = base64_decode($formEncode); // 3
        // $formEncode = html_entity_decode($formEncode); // 1 

        return $formEncode;
    }