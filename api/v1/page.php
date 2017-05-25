<?php

/**
 * 
 * @package page126 API
 * @version  1.0
 * @author  sjamesmccarthy, james@page126.com
 * @copyright  2015
 * @license http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * 
 * @uses http://[domain]/[version]/[endpoint]/[verb]/[id]/ returns JSON
 * 
 * @method __construct
 * @method __destruct
 * @method getById
 * 
 */

class PAGE extends API {

	/* @name __construct */
	/* @desc inherits the parents methods */
	function __construct() {

		/* Since parent constructors are not called implicitly if */
		/* the child class defines a constructor we simpley call it */
		/* here to inherit the parent's methods and bad habits. */

		parent::__construct();

	}

	/* @name GetById */
	/* @param int $id passed in from index.php and created in API class */
	/* @desc fetches a single row from the database */
    public function getById($id=0) {

        /* Making this pretty simple and straight forward without any */
        /* statement preparing for now. */

        $sql="SELECT title, content, shared,last_modified FROM entry WHERE id=" . $this->con->real_escape_string($id);

		$result=mysqli_query($this->con,$sql);

		/* Just make sure at least a single row is returned */
		if(mysqli_affected_rows($this->con) === 1) {
			
			/* Loop through the result associatively */
			while ($row=mysqli_fetch_assoc($result)) {
				
				/* Based on shared status (returned key) and determine whether we will authorize it or not */
				/* 1 = authorized; 0 = not authorized */
				if($row['shared'] == 1) {
					$data = array("title"=>$row['title'], "content"=>$row['content'], "last_modified"=>$row['last_modified']);
					$this->sendResponse(200, json_encode($data));
					return true;
				} else {
					$data=array("title"=>$row['title'], "content"=>'507: ' . $this->getStatusCodeMessage('507'));
					$this->sendResponse(507, json_encode($data));
					return false;
				}
				
			}

			/* You should always free your result with mysqli_free_result(), */
			/* when your result object is not needed anymore. */
			mysqli_free_result($result);

		/* If no results were found for some reason return false and send 404 response */
		/* codes can be found in the API class method: getStatusCodeMessage() */
		} else {
			$data=array("title"=>$row['title'], "content"=>'404: ' . $this->getStatusCodeMessage('404'));
			$this->sendResponse(404, json_encode($data));
			return false;
		}
    }

}