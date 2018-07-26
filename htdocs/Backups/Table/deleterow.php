<?php
$lines = file(__DIR__ . '/content/rows.json');
$last = sizeof($lines) - 1 ; 
unset($lines[$last]); 
unset($lines[$last-1]);
// write the new data to the file 
$fp = fopen(__DIR__ . '/content/rows.json', 'w'); 
fwrite($fp, implode('', $lines)); 
fclose($fp); 
file_put_contents(__DIR__ . '/content/rows.json', "]" , FILE_APPEND);
?>
