<?php
class DB {
	
	private static $user="alex";
	private static $password="";
	private static $database="test";
	private static $location = "localhost:3306";

	public static function executeQuery($query) {
		mysql_connect(self::$location, self::$user, self::$password);
		@mysql_select_db(self::$database) or die ("Impossible de selecionar la base de dades");
		$result = mysql_query($query);
		mysql_close();
		return $result;
	}
	
}
?>