<?php
class ControladorUsuari implements IControladorUsuari {
	
    private static $querySelectAbstract = "SELECT * FROM USUARI WHERE id = '?1';";
	private static $querySelectAll = "SELECT * FROM USUARI;";	
		
    public function obte($id) {
    	$query = str_replace("?1", $id, $querySelectAbstract);
		$result = DB::executeQuery($query);
		// TODO: crear usuari	
    }
	
	public function existeix($id) {
		$query = str_replace("?1", $id, $querySelectAbstract);
		$result = DB::executeQuery($query);
		return mysql_num_rows($result) > 0;
	}
	
	public function tots() {
		$result = DB::executeQuery($querySelectAll);
		// TODO: crear usuaris
	}

}
?>