<?php

class Task
{
    protected $id;
    protected $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function getId(){
        return $this->id ? $this->id : null;
    }

    public function create(){
        $this->db->generateTask();
        $this->id = $this->db->getTaskId();
    }

    public function findFree(){
        return $this->db->findFreeTask();
    }

    public function inProgress($id){
        return $this->db->inProgressTask($id);
    }

    public function findById($id){
        return $this->db->findByIdTask($id);
    }

}