<?php
$input = file_get_contents('php://input'); //this gets all the input data from the POST requests (this will receive the row to edit)
if ($input) {//if no request is made this will be 'false'. Only run this statement when POST is sent
function getLineWithStringNumber($fileName, $str) { //this sets up the function we will use later I'm not going to explain it here
    $lines = file($fileName);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            return $lineNumber;
        }
    }
    return -1;
}
function getLineWithString($fileName, $str) {//this sets up the function we will use later I'm not going to explain it here
    $lines = file($fileName);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            return $line;
        }
    }
    return -1;
}
function str_replace_first($from, $to, $content) //this sets up the function we will use later I'm not going to explain it here
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}
$filename = __DIR__ . '/content/rows.json'; //this is the rows.json file
$data = json_decode( $input, true ); //organize the input data into a json type. This will allow us to index (or search) for the row id 
$id = $data['id']; //this finds the id value of the row 
$str= "\"id\":\"$id\"" ; //this will be the string that we will have to find in the rows.json file that will find the row we want to edit
$line = getLineWithString ($filename, $str); //This will find the actual Line we want to edit in the file 	
$lineNumber = getLineWithStringNumber ($filename, $str); //This will find the Line Number of the line we want to edit 	
if ($lineNumber !== 1) {//if the first row was is the one we want to edit then we need to delete the comma in the first row
$search= ", ";  // we will search for the comma (and space)
$replace = ""; // we will replace it with nothing 
$line= str_replace_first($search,$replace,$line); // this function will replace the first instance of a comma and space in the file (which will be the first row)
}
$search = $line; // we will search for the line we want to delete
$replace = $input."\n"; // and replace it with the new edited row input data with the new line arugument added on(\n)
$srt = file_get_contents($filename); // we get the contents from the file
$srt = str_replace($line,$replace,$srt); // then we run the replace function
file_put_contents($filename, $srt);} // then we put the contents back
?>