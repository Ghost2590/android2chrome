<?php

//namespace Resources;
use \Tonic\Response,
    \stdClass;
//    \Resources\BaseResource;

include("BaseResource.php");

/**
 * Class AuthenticationResource
 *
 * @uri /register
 * @uri /register/:user
 */
class AuthenticationResource extends BaseResource{

    /**
     * @method POST
     * @provides application/json
     * @json
     * @return Tonic\Response
     */
    public function post(){
        $response = new \Tonic\Response();

        $s = new stdClass();
        $s->echo = $this->request->data->user;

        $res = $this->checkAuth($this->request->data->user);
        if ($res) {
            $db = $this->getDBO();

            $sql = <<<EOD
                INSERT INTO
                    `users`
                (
                    {$db->nameQuote('email')},
                    {$db->nameQuote('password')},
                    {$db->nameQuote('logged')}
                )
                VALUES
                (
                    {$db->quote($res->email)},
                    {$db->quote($res->password)},
                    0
                );
EOD;
           $s->insertId = $db->insert($sql);
        }

        $response->code = RESPONSE::OK;
        $response->body = $s;

        return $response;
    }

    /**
     * @method GET
     * @provides application/json
     * @json
     * @param $email
     * @return Tonic\Response
     */
    public function get($email){
        $response = new \Tonic\Response();

        $db = $this->getDBO();

        $sql = <<<EOD
            SELECT
                *
            FROM
                {$db->nameQuote('users')}
            WHERE
                {$db->nameQuote('email')} = {$this->quote($email)}
EOD;

//        var_dump($sql);
       $res = $db->fetchOneRow($sql);
//        var_dump($res);die();

        $response->code = RESPONSE::OK;
        $response->body = $res;

        return $response;
    }

    protected function checkAuth($data) {
        return $data;
    }


}


/**
 * Class LoginResource
 *
 * @uri /login
 * @uri /login/:user
 */
class LoginResource extends BaseResource{

    /**
     * @method POST
     * @provides application/json
     * @json
     * @return Tonic\Response
     */
    public function post(){
        $response = new \Tonic\Response();
        $response->code = RESPONSE::OK;

        $user = $this->request->data->user;

        $res = $this->login($user);
        if (!$res) {
            $user->error = true;
        }
        $response->body = $user;

        return $response;
    }

    private function login($user) {

        $db = $this->getDBO();

        $query = <<<EOD
            SELECT
                *
            FROM
                {$db->nameQuote('users')}
            WHERE
                {$db->nameQuote("email")} = {$db->quote($user->email)}
            AND
                {$db->nameQuote("password")} = {$db->quote($user->password)}
EOD;
        $res = $db->fetchOneRow($query);

        if ($res) {
            $query = <<<EOD
            UPDATE
                {$db->nameQuote('users')}
            SET
                {$db->nameQuote("logged")} = 1
            WHERE
                {$db->nameQuote("email")} = {$db->quote($user->email)}
EOD;
            $res = $db->fetchOneRow($query);
        }

        return $res;
    }

    private function logout($user) {

        $db = $this->getDBO();

        $query = <<<EOD
        UPDATE
            {$db->nameQuote('users')}
        SET
            {$db->nameQuote("logged")} = 0
        WHERE
            {$db->nameQuote("email")} = {$db->quote($user->email)}
EOD;
        $res = $db->fetchOneRow($query);

        return $res;
    }

 }