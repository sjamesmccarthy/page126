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
 * @method createPoints
 * @method getStatusCodeMessage
 * @method sendResponse
 * 
 */

class API {

    public $db;
    public $endpoint;
    public $verb;
    public $id;

    private $DB_NAME;
    private $DB_HOST;
    private $DB_USER;
    private $DB_PSWD;

    /* @name GetById */
	/* @desc This is the PARENT constructor and executes when first called */ 
    /* It will send the CORS headers, segment the URI, check environment */
    /* and create and test the database connection. */
    public function __construct() {

    	/* IMPORANT! Adds Cross Origin Resource Sharing (CORS) support */
    	/* If you want to restrict to specific domains please replace * with */
    	/* the domain name. IE 7 has NO support for CORS, IE 8 and 9 has */
    	/* limited support with the XDomainRequest object. Please read this stackoverlfow */
    	/* post, http://bit.ly/1CdaShm, for more information. */
    	header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        /* Going to extract the URI segments into something usable */
        $this->createPoints();

        /* Database connection information */
		$host = explode('.', $_SERVER['HTTP_HOST']);

		/* Multiple enviroment support */
		/* This looks at the TLD of the URI to determine dev, prod, etc. */
		switch ($host[0])
		{
			case in_array('dev', $host):
			/* Site Database Information */
			$DB_NAME = 'jamesmcc_pageonetwentysix';
			$DB_HOST = 'localhost';
			$DB_USER = 'root';
			$DB_PSWD = 'jewel4me';
			break;

			default:
			/* Site Database Information */
			$DB_NAME = 'btpress_page126';
			$DB_HOST = 'localhost';
			$DB_USER = 'btpress_jamesmcc';
			$DB_PSWD = 'jewel4me';
			break;
		}

		/* Create a new database object with above credentials, etc. */
        $this->con = new mysqli($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME);

        /* Check the datavase connection */
		if ($this->con->connect_error) {
		    die("Database connection failed: " . $this->con->connect_error);
		}

    }

    /* @name __destruct */
	/* @desc Destructor simply closes the database connection */
    public function __destruct() {
        $this->con->close();
    }

    /* @name createPoints */
	/* @desc Make something useful out of the URI for processing */
    /* Also using PHP's sanitize filters for quick cleaning of data passed along the URI */
    /* You may want to filter specific by there type for more strict santizing */
    private function createPoints() {

    	foreach ($_REQUEST as $key => $value) {
    		$this->$key = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    	}

    }


	/* @name GetById */
	/* @param int $status */
	/* @desc Just gives semantic meaning to the status code and then returns the array value. */
    /* We have added some page126 specific codes used by the endpoint models. */
	public function getStatusCodeMessage($status)
	{

	    $codes = Array(
	        100 => 'Continue',
	        101 => 'Switching Protocols',
	        200 => 'OK',
	        201 => 'Created',
	        202 => 'Accepted',
	        203 => 'Non-Authoritative Information',
	        204 => 'No Content',
	        205 => 'Reset Content',
	        206 => 'Partial Content',
	        300 => 'Multiple Choices',
	        301 => 'Moved Permanently',
	        302 => 'Found',
	        303 => 'See Other',
	        304 => 'Not Modified',
	        305 => 'Use Proxy',
	        306 => '(Unused)',
	        307 => 'Temporary Redirect',
	        400 => 'Bad Request',
	        401 => 'Unauthorized',
	        402 => 'Payment Required',
	        403 => 'Forbidden',
	        404 => 'Not Found',
	        405 => 'Method Not Allowed',
	        406 => 'Not Acceptable',
	        407 => 'Proxy Authentication Required',
	        408 => 'Request Timeout',
	        409 => 'Conflict',
	        410 => 'Gone',
	        411 => 'Length Required',
	        412 => 'Precondition Failed',
	        413 => 'Request Entity Too Large',
	        414 => 'Request-URI Too Long',
	        415 => 'Unsupported Media Type',
	        416 => 'Requested Range Not Satisfiable',
	        417 => 'Expectation Failed',
	        500 => 'Internal Server Error',
	        501 => 'Not Implemented',
	        502 => 'Bad Gateway',
	        503 => 'Service Unavailable',
	        504 => 'Gateway Timeout',
	        505 => 'HTTP Version Not Supported',
	        /* Pgae126 Specific Codes */
	        506 => 'Method Not Available',
	        507 => 'Requested Entry Marked Private'
	    );

    	return ($codes[$status]);
	}

	/* @name sendResponse */
	/* @param $status default 200, OK */
	/* @param $body default NULL */
	/* @param $content_type default text/html could be application/json or application/xml (not supported output in this version) */
	/* @desc And finally lets send the response back. If no status is passed 200 (success) will be the default */
	/* The getStatusCodeMessage() method above will provide descriptions to the codes */
	public function sendResponse($status = 200, $body = '', $content_type = 'text/html')
	{
	    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
	    header("Access-Control-Allow-Origin: *");
	    header('Content-type: ' . $content_type);
	    echo $body;
	}


}