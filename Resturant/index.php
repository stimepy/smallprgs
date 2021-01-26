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
if($max>0){
	$select = rand(0,($max-1));
}
else{
	echo "no list present."
	exit(0);
}
echo $listof[$select];

/* Todo: Save the last food place that was chosen and accepted.*/

?>