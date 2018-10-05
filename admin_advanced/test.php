<?php

$count = 0;

add($count);
add($count);

echo $count;

function add(&$index){
	$index++;
}

?>