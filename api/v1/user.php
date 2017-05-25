<?php

class USER extends API {

	function __construct() {

		/* Database connection information */
		$host = explode('.', $_SERVER['HTTP_HOST']);

		switch ($host[0])
		{
			case in_array('dev', $host):
			/* Site Database Information */
			$DB_NAME = 'jamesmcc_pageonetwentysix';
			$DB_HOST = 'localhost';
			$DB_USER = 'root';
			$DB_PSWD = 'ilov3youA';
			break;

			default:
			/* Site Database Information */

			$DB_NAME = 'jamesmcc_pageonetwentysix';
			$DB_HOST = 'localhost';
			$DB_USER = 'jamesmcc_sql';
			$DB_PSWD = 'p3d1cab!';
			break;
		}

        $this->con = new mysqli($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME);
	}

	// Main method to redeem a code
    public function getById($id) {

        /* Establish connection to the DB */
       
        $sql="SELECT * FROM entry WHERE id=$id AND shared=1";
		$result=mysqli_query($this->con,$sql);

		if(mysqli_affected_rows($this->con) > 0) {
			
			while ($row=mysqli_fetch_assoc($result)) {
				$data = array("title"=>$row['title'], "content"=>$row['content']);
			}

			mysqli_free_result($result);
			parent::sendResponse(200, json_encode($data));
			return true;

		} else {
			$data=NULL;
			parent::sendResponse(404, json_encode($data));
			return false;
		}
    }

}