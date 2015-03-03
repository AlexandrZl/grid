<?php 

class App
{
	protected $route;
	protected $config;

	public function __construct()
	{
		$this->route = explode('/', $_SERVER['REQUEST_URI']);
	}

	public function run(){
		foreach ($this->route as $key => $value) {
			if ($value == "api") {
				return $this->api($key);
			}
		}
	}

	protected function api($key)
	{
		foreach ($this->route as $key => $value) {
			$value = explode("?", $value);
			if ($value[0] == "loginUser") {
				return $this->login();
			}
			if ($value[0] == "registerUser") {
				return $this->register();
			}
			if ($value[0] == "logout") {
				return $this->logout();
			}
		}
	}

	protected function login()
	{
		$login = $_GET['login'];
		$pass = $_GET['pass'];

		$data = array(
			'login' => $_GET['login'],
			'pass' => $_GET['pass'],
		);

		$db = new Db();
		$db->login($data);
	}

	protected function register()
	{
		$login = $_GET['login'];
		$pass = $_GET['pass'];

		$data = array(
			'login' => $_GET['login'],
			'pass' => $_GET['pass'],
		);

		$db = new Db();
		$db->create($data);
	}

	protected function logout()
	{
		$db = new Db();
		$db->logout();
	}
}

?>