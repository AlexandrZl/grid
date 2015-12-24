<?php

class Client
{
    protected $db;
    protected $hash;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function create()
    {
        return $this->db->createClient();
    }

    public function findByHash($hash)
    {
        return $this->db->findByHash($hash);
    }

    public function findById($id)
    {
        return $this->db->clinedFindById($id);
    }
}