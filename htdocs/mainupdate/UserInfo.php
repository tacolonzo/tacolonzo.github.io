<?php
$fh = fopen(__DIR__ . '/content/user.json', 'r+'); //opens the row.json file for reading and writing (dir is the current directory, so you dont have to say C:/ bla bal)
$stat = fstat($fh); //read the information about the file, you can use the ['size'] index to read how many characters the file has
ftruncate($fh, $stat['size']-1); // this shortens (or truncates) the file by 1 character (deleting closing bracket ']')
fclose($fh); //closes the file we just opened
$input = file_get_contents('php://input'); //this gets all the input data from the POST requests (this will receive the new rows)
if ($input){ //if no request is made this will be 'false'. Only run this statement when POST is sent
if($stat['size']< 5) {	// if the file is less than 5 characters (4 characters make the two brackets and newline) then the file has now rows
file_put_contents(__DIR__ . '/content/user.json', "$input \n]" , FILE_APPEND);} //if no rows then save the new row (without comma) and close the bracket back with newline '\n]'
else { // if there are other rows then
file_put_contents(__DIR__ . '/content/user.json', ", $input \n]" , FILE_APPEND);} // save the new row at the end of the file (with comma for formatting) and close the bracket back
}	
else {// if no request is made then put the closing bracket back 
file_put_contents(__DIR__ . '/content/user.json', "]" , FILE_APPEND);
}	
?>