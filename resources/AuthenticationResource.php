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
 * @uri /auth/:op/:user
 */
class AuthenticationResource extends BaseResource{

    /**
     * @method POST
     * @json
     * @return Tonic\Response
     */
    function noOp() {
        $response = new Response();
        $response->code = Response::BADREQUEST;
        return $response;
    }

    /**
     * @method PUT
     * @provides application/json
     * @onlyOp register
     * @json
     * @return Tonic\Response
     */
    public function register(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;

        $model = new Models\AuthenticationModel();

        $user = $model->register($user);

        if (!$user){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }

    /**
     * @method UPDATE
     * @provides application/json
     * @onlyOp login
     * @json
     * @return Tonic\Response
     */
    public function login(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;

        $model = new Models\AuthenticationModel();

        $res = $model->login($user);

        if (!$res){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }

    /**
     * @method UPDATE
     * @provides application/json
     * @onlyOp logout
     * @json
     * @return Tonic\Response
     */
    public function logout(){
        $response = new Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;

        $model = new Models\AuthenticationModel();

        $user = $model->logout($user);

        if (!$user){
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

        $model = new Models\AuthenticationModel();

        $user = $model->delete($user);

        if (!$user){
            $response->code = RESPONSE::BADREQUEST;
        }
        $response->body = $user;

        return $response;
    }

    public function checkIfAccount($email){

    }
}