<?php
class DB {
	
	private static $user="username";
	private static $password="password";
	private static $database="database";
	private static $location = localhost;

	public static function executeQuery($query) {
		mysql_connect($location, $user, $password);
		@mysql_select_db($database) or die ("Impossible de selecionar la base de dades");
		$result = mysql_query($query);
		mysql_close();
		return $result;
	}
	
}
?>