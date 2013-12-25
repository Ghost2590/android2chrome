<?php

namespace Android2Chrome\Resource;

use Tonic,
    Tonic\Response,
    \stdClass,
    Android2Chrome,
    Android2Chrome\Models;

include("BaseResource.php");

/**
 * Class AuthenticationResource
 *
 * @uri /links/:op/:user/
 * @uri /links/:op/:user/:link
 */
class LinksResource extends BaseResource{

    /**
     * @method GET
     * @json
     * @return Tonic\Response
     */
    function noOp() {
        $response = new Response();
        $response->code = Response::BADREQUEST;
        return $response;
    }

    /**
     * @method GET
     * @provides application/json
     * @onlyOp get
     * @json
     * @return Tonic\Response
     */
    public function get(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;

        $model = new Models\LinksModel();

        $user = $model->getLinks($user);

        if (!$user){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }

    /**
     * @method PUT
     * @provides application/json
     * @onlyOp insert
     * @json
     * @return Tonic\Response
     */
    public function insert(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;
        $link = $this->request->data->link;

        $model = new Models\LinksModel();

        $res = $model->insertLink($user, $link);

        if (!$res){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }

    /**
     * @method UPDATE
     * @provides application/json
     * @onlyOp update
     * @json
     * @return Tonic\Response
     */
    public function updateLink(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;
        $link = $this->request->data->link;

        $model = new Models\LinksModel();

        $res = $model->updateLink($user, $link);

        if (!$res){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }

    /**
     * @method DELETE
     * @provides application/json
     * @onlyOp delete
     * @json
     * @return Tonic\Response
     */
    public function delete(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;
        $link = $this->request->data->link;

        $model = new Models\LinksModel();

        $user = $model->deleteLink($user, $link);

        if (!$user){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }

    /**
     * @method DELETE
     * @provides application/json
     * @onlyOp deleteAll
     * @json
     * @return Tonic\Response
     */
    public function deleteAll(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;

        $model = new Models\LinksModel();

        $user = $model->deleteAllLinks($user);

        if (!$user){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }
}