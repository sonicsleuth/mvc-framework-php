<?php 
/** 
 * Access to globals require loading this helper file into
 * a Controller within the __construct() like so:
 * $this->load_helper(['global_constants']);
 */

// Global Constantce for this Application.
// Const values can be accessed directly within a Controller by NAME.
define("SOME_CONSTANT","Some Value");

// EXAMPLE - Returning Arrays to Controllers
// In your Controller create an empty variable 
// then call your global function passing in 
// that variable, for example:
// $myList;
// getMyList($myList);
// 
// Your global function MUST reference '&' the variable.
// Changes made within your function to the ref $var
// will be made to the original $var, not a copy of it.
function getMyList(&$myList) {
    $myList = [
        'one' => 1,
        'two' => 2,
        'three' => 3
    ];
}