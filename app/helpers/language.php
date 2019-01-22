<?php
/*
 * Helpers, as the name suggests, help you with tasks.
 * Each helper file is simply a collection of functions in a particular category.
 */

 function language_url($url) {

    if(isset($_COOKIE['language'])) {
        return '/' . $_COOKIE['language'] . $url;
    } else {
        return $url;
    }
 }