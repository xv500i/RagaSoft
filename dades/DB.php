<?php
class DB {
	
	private static $user="jz3xhy55_ragasof";
	private static $password="ragasof123";
	private static $database="jz3xhy55_test";
	//private static $user="alex";
	//private static $password="";
	//private static $database="test";
	private static $location = "localhost";

	public static function executeQuery($query) {
		mysql_connect(self::$location, self::$user, self::$password);
		@mysql_select_db(self::$database) or die ("Impossible de selecionar la base de dades");
		$result = mysql_query($query);
		mysql_close();
		//echo $query . "<br>";
		return $result;
	}
	
}
?>