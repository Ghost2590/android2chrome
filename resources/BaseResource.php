<?php
namespace Android2Chrome;
//namespace Resources;

use Tonic\Application;
use Tonic\Request;
use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

include_once('lib/db/databasemysql.php');
//include_once('lib/db/mysqlresultset_old.php');

class BaseResource extends Resource {

    public function __construct(Application $app, Request $request, array $urlParams, $checkToken=true)
    {
        parent::__construct($app, $request, $urlParams);
    }


    /**
     * Condition method to turn output into JSON.
     *
     * This condition sets a before and an after filter for the request and response. The
     * before filter decodes the request body if the request content type is JSON, while the
     * after filter encodes the response body into JSON.
     */
    protected function json()
    {
        $this->before(function ($request) {
            if ($request->contentType == "application/json") {
                $request->data = json_decode($request->data);
            }
        });
        $this->after(function ($response) {
            $response->contentType = "application/json";
            if (isset($_GET['jsonp'])) {
                $response->body = $_GET['jsonp'].'('.json_encode($response->body).');';
            } else {
                $response->body = json_encode($response->body);
            }
        });
    }

    /**
     * Condition method for above methods.
     *
     * Only allow specific :op parameter to access the method
     */
    protected function onlyOp($allowedOp){
        if (strtolower($allowedOp) != strtolower($this->op)) throw new ConditionException;
    }

    protected function getDBO ($type = 'read') {
//        $db = MySqlDatabase::getInstance();
//        try {
//            $db->connect('localhost', 'root', '', 'android2chrome');
//            return $db;
//        }
//        catch (Exception $e) {
//            die($e->getMessage());
//        }
        try {
            $db = new WSDBMysql($type);
//            $db->connect('localhost', 'root', '', 'android2chrome');
            return $db;
        }
        catch (Exception $e) {
            die($e->getMessage());
        }

    }
}