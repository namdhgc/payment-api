<?php

namespace Src\Repositories;

class UserRepository
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, firstname, lastname, firstparent_id, secondparent_id
            FROM
                person
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
