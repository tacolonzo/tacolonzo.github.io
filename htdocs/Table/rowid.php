<?php
function getLineWithString($fileName, $str) { //This is a function we will use later, I will explain later.
    $lines = file($fileName);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            return $lineNumber;
        }
    }
    return -1;
}
$filename = __DIR__ . '/content/rows.json';	 //this is just to save the filename (I've been copying this for each code)
$id = 0;// this initialized the id value to zero (we will be using a loop) 
$something = 0; // this initialized another variable to zero, we will talk about what it does later
while ($something !== -1){ //while this variable does not equal -1 then continue the following loop 
$id++;	// first increase the id (goes from 1 - infinity) 
$str= "\"id\":\"$id\"" ; //and creat this string that we will use to search in the file
$something = getLineWithString ($filename, $str); // this will find the line number of the string we just setup, if no string is found then this will return -1 
}
echo $id; //then tell the website (or javascript call) what id number is next by echoing 
?>