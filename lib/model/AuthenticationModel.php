<?php
namespace Android2Chrome\Models;

// defined('WS_EXEC') or die('Restricted access');

use Android2Chrome\BaseModel;

class AuthenticationModel extends BaseModel{


    public function login($user){

//        $res = $this->checkAuth($this->request->data->user);
        $db = $this->getDBO();

        $sql = <<<EOD
        SELECT count(*)
        FROM
            "users"
        WHERE
            "email" = {$db->quote($user->email)}
        AND
            "password" = {$db->quote($user->password)}
EOD;
        $db->setQuery($sql);
        $res = $db->loadResult();

        if (!$res) {
//
//            $sql = <<<EOD
//                INSERT INTO
//                    `users`
//                (
//                    {$db->nameQuote('email')},
//                    {$db->nameQuote('password')},
//                    {$db->nameQuote('logged')}
//                )
//                VALUES
//                (
//                    {$db->quote($res->email)},
//                    {$db->quote($res->password)},
//                    0
//                );
//EOD;
//            $s->insertId = $db->insert($sql);

            return false;
        } else {
            $db = $this->getDBO("write");
            $sql = <<<EOD
                UPDATE
                    "users"
                SET
                    "logged" = 1
                WHERE
                    "email" = {$db->quote($res->email)}
EOD;
            $db->setQuery($sql);
            return $db->query();
        }
    }

    public function logout($user){

        $db = $this->getDBO("write");
        $sql = <<<EOD
            UPDATE
                "users"
            SET
                "logged" = 0
            WHERE
                "email" = {$db->quote($user->email)}
EOD;
        $db->setQuery($sql);
        return $db->query();
    }

    public function register($user){

//        $res = $this->checkAuth($this->request->data->user);
        $db = $this->getDBO();

        $sql = <<<EOD
        SELECT count(*)
        FROM
          "users"
        WHERE
          "email" = {$db->quote($user->email)}
EOD;
        $db->setQuery($sql);
        $res = $db->loadResult();

        if (!$res) {
//
            $db = $this->getDBO("write");
            $sql = <<<EOD
                INSERT INTO
                    "users"
                (
                    "email",
                    "password",
                    "logged"
                )
                VALUES
                (
                    {$db->quote($res->email)},
                    {$db->quote($res->password)},
                    0
                );
EOD;
            $db->setQuery($sql);
            return $db->query();
        } else {
            return false;
        }
    }

    public function delete($user){

//        $res = $this->checkAuth($this->request->data->user);
        $db = $this->getDBO();

        $sql = <<<EOD
        SELECT count(*)
        FROM
            "users"
        WHERE
            "email" = {$db->quote($user->email)}
        AND
            "password" = {$db->quote($user->password)}
EOD;
        $db->setQuery($sql);
        $res = $db->loadResult();

        if (!$res) {
//
//            $sql = <<<EOD
//                INSERT INTO
//                    `users`
//                (
//                    {$db->nameQuote('email')},
//                    {$db->nameQuote('password')},
//                    {$db->nameQuote('logged')}
//                )
//                VALUES
//                (
//                    {$db->quote($res->email)},
//                    {$db->quote($res->password)},
//                    0
//                );
//EOD;
//            $s->insertId = $db->insert($sql);

            return false;
        } else {
            $db = $this->getDBO("write");
            $sql = <<<EOD
                DELETE FROM
                    "users"
                WHERE
                    "email" = {$db->quote($user->email)}
                AND
                    "password" = {$db->quote($user->password)}
EOD;
            $db->setQuery($sql);
            return $db->query();
        }
    }
}