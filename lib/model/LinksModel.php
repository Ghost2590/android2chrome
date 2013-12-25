<?php
namespace Android2Chrome\Models;

// defined('WS_EXEC') or die('Restricted access');

use Android2Chrome\BaseModel;

class LinksModel extends BaseModel{

    public function getLinks($user){

        $db = $this->getDBO("read");

        $sql = <<<EOD
            SELECT *
            FROM
                "links" "l"
            LEFT JOIN
                "users" "u"
            ON
                "u"."email" = {$db->quote($user->email)}
            WHERE
                "l"."user_id" = "u"."id"
EOD;
        $db->setQuery($sql);
        return $db->loadResultObject();
    }

    public function deleteLink($user, $link){

        $db = $this->getDBO("read");

        $sql = <<<EOD
            DELETE FROM
                "links" "l"
            LEFT JOIN
                "users" "u"
            ON
                "u"."email" = {$db->quote($user->email)}
            WHERE
                "l"."user_id" = "u"."id"
            AND
                "link" = {$db->quote($link)}
EOD;
        $db->setQuery($sql);
        return $db->query();
    }

    public function insertLink($user, $link){

        $db = $this->getDBO("write");

        $sql = <<<EOD
            INSERT INTO
                "links" "l"
                ("link", "user_id", "readed")
            VALUES
                (
                    {$db->quote($link)},
                    SELECT "u"."id" FROM "users" "u" WHERE "u"."email" = {$db->quote($user->email)},
                    0
                )
EOD;
        $db->setQuery($sql);
        return $db->query();
    }

    public function updateLink($user, $link){

        $db = $this->getDBO("write");

        $sql = <<<EOD
            UPDATE
                "links"
            SET
                "readed" = 1
            WHERE
                "links"."user_id" = SELECT "u"."id" FROM "users" "u" WHERE "u"."email" = {$db->quote($user->email)}
            AND
                "links"."link" = {$db->quote($user->link)}
EOD;
        $db->setQuery($sql);
        return $db->query();
    }

    public function deleteAllLinks($user){

        $db = $this->getDBO("read");

        $sql = <<<EOD
            DELETE FROM
                "links" "l"
            LEFT JOIN
                "users" "u"
            ON
                "u"."email" = {$db->quote($user->email)}
            WHERE
                "l"."id" = "u"."id"
EOD;
        $db->setQuery($sql);
        return $db->query();
    }


}