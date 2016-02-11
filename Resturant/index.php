<?php
/**
 *
 * User: ksherrerd
 * Date: 1/21/13
 *
 *
 */


$listof=file('list');

$max = sizeof($listof);

$select = rand(0,($max-1));

echo $listof[$select];

/* Todo: Save the last food place that was chosen and accepted.*/

?>