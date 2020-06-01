<?php
//// start session //////
if (!isset($_SESSION)) {
	session_start();
//  ini_set('session.gc_maxlifetime',87000);
}




//// application name ////
$application_name='Tic Tac Toc';
//// end application name ////



////// defining the server ////
$server='http://localhost/bingo/';
////// end define the server ////

$hostname_website = "localhost";
$database_website = "bingo";
$username_website = "root";
$password_website = "";
$website = mysqli_connect($hostname_website, $username_website, $password_website) or trigger_error(mysqli_error($website),E_USER_ERROR);

mysqli_query($website , "SET NAMES utf8");
mysqli_query($website , "SET SESSION SQL_BIG_SELECTS=1;");


mysqli_select_db ( $website, $database_website );
// mysqli_free_result($current_employee);

function selectItem( $table, $where, $operation, $value ) {
	global $website;
	global $database_website;
	mysqli_select_db ( $website, $database_website );
	$query_item = "SELECT * FROM $table WHERE $where $operation '$value'";
	$item = mysqli_query ( $website, $query_item ) or die( mysqli_error ( $website ) );
	return $row_item = mysqli_fetch_assoc ( $item );
}
?>
