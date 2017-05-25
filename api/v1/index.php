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
 * @class API 
 * @class PAGE extends API
 * 
 */

/* Create the API object */
include('api.php');
$api = new API;

/* Extend the API based on endpoint needed */
/* $api->endpoint is set in the API::createPoints() method */
/* We will use a variable variable to create the endpoints object */
include($api->endpoint . '.php');
${$api->endpoint} = new $api->endpoint;

/* Each endpoint can have either GET, PUT, POST DELETE options */
/* This is also where you will switch between the specific requests of the endpoint */
/* E.g., fetch a single record or a listing of records. You may have an IF/ELSEIF */
/* statement inside the "get" case calling different methods. */
/* Reference: http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html */
switch($api->verb) {

	case "get":
		/* Retrieves an object from the origin server as requested by the parameters of the URI */
		${$api->endpoint}->getById($api->id);
		break;

	case "put":
		/* The PUT method is used to modify an exsiting object on the origin server */
		echo $api->getStatusCodeMessage(506);
		break;

	case "post":
		/* The POST method is used to request new data be added to the origin server */
		echo $api->getStatusCodeMessage(506);
		break;

	case "delete":
		/* The DELETE method requests that the origin server delete the resource identified by the Request-URI. */
		echo $api->getStatusCodeMessage(506);
		break;

	default:
		/* if no operation is included an error is returned */
		echo $api->getStatusCodeMessage(506);
		break;
		
}

?>