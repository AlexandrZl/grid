<?php

class Db
{
	protected $config;
	protected $connect;

	public function __construct()
	{
		$this->config = include 'autoload/local.php';
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
			$sql = "CREATE TABLE user (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(50) NOT NULL,
				pass VARCHAR(255) NOT NULL,
				token VARCHAR(255),
				UNIQUE (name)
			)";
			$this->connect->exec($sql);
		}
		catch(PDOException $e)
		{
		}
        try {
            $sql = "CREATE TABLE task (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				task VARCHAR(50) NOT NULL,
				response VARCHAR(255) NOT NULL
			)";
            $this->connect->exec($sql);
        }
        catch(PDOException $e)
        {
        }
        try {
            $sql ="CREATE TABLE user_task (
             id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             user INT ( 6 ) NOT NULL,
             task INT ( 6 ) NOT NULL)";
            $this->connect->exec($sql);
        } catch(PDOException $e)
        {
        }

        try {
            $sql = "INSERT INTO task (id, task, response) VALUES
            (1, '1+1', '2'),
            (2, '2+2', '4')";

            $this->connect->exec($sql);
        } catch(PDOException $e)
        {
        }
	}

    public function getAllTask()
    {
        $sql = $this->connect->prepare("select * from task");
        $sql->execute();
        $task = $sql->fetchAll();

        return $task;
    }

    public function getResolveTaskByUser($id)
    {
        $sql = $this->connect->prepare("select * from user_task where user = :id");
        $sql->execute(array(
            ':id' => $id,
        ));
        $resolveTask = $sql->fetchAll();

        return $resolveTask;
    }

    public function isResolvedTask($userId, $taskId)
    {
        $sql = $this->connect->prepare("select * from user_task where user = :userId AND task = :taskId");
        $sql->execute(array(
            ':userId' => $userId,
            ':taskId' => $taskId
        ));
        $resolvedTask = $sql->fetch();

        return $resolvedTask;
    }

    public function findTaskById($id)
    {
        $sql = $this->connect->prepare("select * from task where id = :id");
        $sql->execute(array(
            ':id' => $id
        ));
        $task = $sql->fetch();

        return $task;
    }

	public function login($login)
	{
		$sql = $this->connect->prepare("select * from user where name = :name and pass = :pass");
		$sql->execute(array(
			':name' => $login['login'],
			':pass' => md5($login['pass']),
		));
		$user = $sql->fetch();

		return $user;
	}

    public function taskResolved($userId, $taskId)
    {
        $sql = "INSERT INTO user_task (user, task) VALUES (:userId, :taskId)";
        $q = $this->connect->prepare($sql);
        try {
            $q->execute(array(':userId'=> $userId, ':taskId' => $taskId ));
            $result = true;
        }
        catch(Exception $e) {
            $result = false;
        }
        return $result;
    }

	public function create($data)
	{
		$sql = "INSERT INTO user (name, token, pass) VALUES (:name, :token, :pass)";
		$q = $this->connect->prepare($sql);
		try {
			$q->execute(array(':name'=>$data['login'], ':token'=>md5(time()), ':pass' => md5($data['pass'])));
            $result = true;
		}
		catch(Exception $e) {
            $result = false;
		}
        return $result;
	}

	public function logout()
	{
        session_destroy();
        $_SESSION = null;
	}
}