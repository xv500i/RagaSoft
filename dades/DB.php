<?php
class DB {
	
	private static $user="progr724_Rsoft";
	private static $password="ragasoft";
	private static $database="progr724_ragasoft";
	private static $location = "76.74.242.180";

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