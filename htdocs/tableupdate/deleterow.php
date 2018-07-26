<?php
$input = file_get_contents('php://input'); //this gets all the input data from the POST requests (this will receive the row to delete)
if ($input) {//if no request is made this will be 'false'. Only run this statement when POST is sent
function getLineWithString($fileName, $str) { //this sets up the function we will use later I'm not going to explain it here
    $lines = file($fileName);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            return $lineNumber;
        }
    }
    return -1;
}
function str_replace_first($from, $to, $content)//this sets up the function we will use later I'm not going to explain it here
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}
$filename = __DIR__ . '/content/rows.json'; //this is the rows.json file 
$data = json_decode( $input, true ); //organize the input data into a json type. This will allow us to index (or search) for the row id 
$id = $data['id']; //this finds the id value of the row 
$str= "\"id\":\"$id\"" ; //this will be the string that we will have to find in the rows.json file that will find the row we want to delete
$delete = getLineWithString ($filename, $str); //This will find the Line Number of the line we want to delete 	
$lines = file($filename);// This will save the contents in the file (and organize by line) 
unset($lines[$delete]); //This will delete (or unset) the values in the file coresponding to the line number we found in the file ($delete)
$fp = fopen($filename, 'w'); // this will setup the file for writing 
fwrite($fp, implode('', $lines)); // then we will write the edited lines back to the file  (implode just puts the lines together)
fclose($fp); //then we close the file 
if ($delete == 1){ //if the first row was deleted then we need to delete the comma in the first row
$srt = file_get_contents($filename); // this is still the file we want to edit, but we need to get all of the contents now (not just open it)
$search= ", ";  // we will search for the comma (and space)
$replace = ""; // we will replace it with nothing 
$srt= str_replace_first($search,$replace,$srt); // this function will replace the first instance of a comma and space in the file (which will be the first row)
file_put_contents($filename, $srt);} // then we put the updated contents back into the file.
}
?>
