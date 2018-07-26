<?php
$fh = fopen(__DIR__ . '/content/rows.json', 'r+');
$stat = fstat($fh);
ftruncate($fh, $stat['size']-1);
fclose($fh);
$input = file_get_contents('php://input');
if ($input){
file_put_contents(__DIR__ . '/content/rows.json', ", $input \n ]" , FILE_APPEND);}
else {
file_put_contents(__DIR__ . '/content/rows.json', "]" , FILE_APPEND);}	
?>
