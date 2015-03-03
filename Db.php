<?php

class Db
{
	protected $config;
	protected $connect;

	public function __construct()
	{
		session_start(); 
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
		    	echo "Connection failed: " . $e->getMessage();
		    }

		try {
			$sql = "CREATE TABLE users (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
				name VARCHAR(50) NOT NULL,
				pass VARCHAR(255) NOT NULL,
				token VARCHAR(255)
			)";
			$this->connect->exec($sql);
		}
		catch(PDOException $e)
		{
			return;
		}
	}

	public function login($login)
	{
		$sql = $this->connect->prepare("select * from users where name = :name and pass = :pass");
		$sql->execute(array(
			':name' => $login['login'],
			':pass' => md5($login['pass']),
		));
		$user = $sql->fetch();

		if($user) {
			if (isset($_SESSION['authUser'])) {
				if ($user['name'] == $_SESSION['authUser']['name']) {
					echo "You already logined";
				} else {
					echo $_SESSION['authUser']['name']." is active. Logout";
				}
			} else {
				$_SESSION['authUser'] = $user;
				echo "You logined";
			}
			
		} else {
			echo "Login/Pass bad";
		}
	}

	public function create($data)
	{
		$sql = "INSERT INTO users (name, token, pass) VALUES (:name, :token, :pass)";
		$q = $this->connect->prepare($sql);
		$q->execute(array(':name'=>$data['login'], ':token'=>md5(time()), ':pass' => md5($data['pass'])));
	}

	public function logout()
	{
		session_destroy();
	}


}