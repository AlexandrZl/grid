<?php

class Db
{
	protected $config;
	protected $connect;

	public function __construct()
	{
		$this->config = include 'autoload/global.php';
		$this->connect();
	}

	protected function connect()
	{
		$config = $this->config['db'];

		try {
				$this->connect = new PDO($config['dsn'], $config['username'], $config['password']);
			    $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    }
		catch(PDOException $e)
		    {
		    }
        try {
            $sql = "CREATE TABLE task (
				id INT(6) AUTO_INCREMENT PRIMARY KEY,
				status BOOLEAN NULL DEFAULT FALSE,
				inProgress BOOLEAN NULL DEFAULT FALSE,
				end_time int(11) NULL
			)ENGINE=INNODB";
            $this->connect->exec($sql);
        }
        catch(PDOException $e)
        {
        }

        try {
            $sql = "CREATE TABLE node (
				id INT(6) AUTO_INCREMENT PRIMARY KEY,
				status BOOLEAN NULL DEFAULT FALSE,
				ready BOOLEAN NULL DEFAULT TRUE,
				task_id int(3) NULL,
				fault_tolerance int(11) NULL,
				INDEX task_id (task_id),
				FOREIGN KEY (task_id) REFERENCES task(id)
			)ENGINE=INNODB";
            $this->connect->exec($sql);
        }
        catch(PDOException $e)
        {
        }
	}

    public function createNode() {
        $faultTolerance = rand(30, 99);
        $sql = "INSERT INTO node (id, status, task_id, fault_tolerance) VALUES ('', true, null, $faultTolerance)";
        $this->connect->exec($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $sql = $this->connect->prepare($sql);
        $sql->execute();
    }

    public function generateTask() {
        $sql = "INSERT INTO task (id, status) VALUES ('', false)";
        $this->connect->exec($sql);
    }

    public function getTaskId() {
        $id = $this->connect->lastInsertId();
        return $id;
    }

    public function findFreeNode() {
        $sql = $this->connect->prepare("select * from node where ready = 1");
        $sql->execute();
        $node = $sql->fetch();

        return $node;
    }

    public function findFreeTask() {
        $sql = $this->connect->prepare("select * from task where status = 0 and inProgress = 0");
        $sql->execute();
        $task = $sql->fetch();

        return $task;
    }

    public function runTaskForNode($idNode, $idTask) {
        $sql = $this->connect->prepare("UPDATE node SET ready = :ready, task_id = :idTask WHERE id = :id");

        $sql->execute(array(
            ':ready' => 0,
            ':idTask' => $idTask,
            ':id' => $idNode
        ));
        $sql->execute();
    }

    public function inProgressTask($id) {
        $sql = $this->connect->prepare("UPDATE task SET inProgress = :inProgress, end_time = :end_time WHERE id = :id");

        $sql->execute(array(
            ':inProgress' => 1,
            ':end_time' => time(),
            ':id' => $id
        ));
        $sql->execute();
    }

}