<?php

class Node
{
    protected $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function create(){
        $this->db->createNode();
    }

    public function findFree(){
        return $this->db->findFreeNode();
    }

    public function runTask($idNode, $idTask){
        $this->db->runTaskForNode($idNode, $idTask);
    }
}