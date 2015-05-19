<?php 

class App
{
	private $route;
    private $response;
    private $authUser;

	public function __construct()
	{
        $this->route = new Route();
        $this->response = new XMLResponse();
        $this->authUser = new User();
	}

	public function run()
    {
        $cmd = $this->route->getRoute();

        switch(true) {
            case $cmd == "loginUser":
                $this->login();
                break;
            case $cmd == "registerUser":
                $this->register();
                break;
            case $cmd == "logout":
                $this->logout();
                break;
            case $cmd == "getTask":
                $this->getTask();
                break;
            case $cmd == "resolveTask":
                $this->resolveTask();
                break;
        }
	}

	protected function login()
	{
        $response = array();
		$data = array(
			'login' => $_GET['login'],
			'pass' => $_GET['pass'],
		);

		$db = new Db();
		$user =  $db->login($data);

        if($user) {
            if ($this->authUser->hasIdentity()) {
                if ($user['name'] == $this->authUser->getUserName()) {
                    $response =  array("status" => "1", "type" => "login", "message" => "You already logined");
                }
            } else {
                $this->authUser->authUser($user);
                $response =  array("status" => "0", "type" => "login", "message" => "You are logined");
            }
        } else {
            $response =  array("status" => "2", "type" => "login", "message" => "Login/Pass bad");
        }

        $this->response->out($response);
	}

    protected function register()
    {
        $data = array(
            'login' => $_GET['login'],
            'pass' => $_GET['pass'],
        );

        if ($this->authUser->hasIdentity()) {
            $response =  array("status" => "2", "type" => "register", "message" => "You must logout");
            $this->response->out($response);
            return 0;
        }

        $db = new Db();
        $newUser = $db->create($data);

        if ($newUser) {
            $response =  array("status" => "0", "type" => "register", "message" => "New user with name: ".$data['login']);
            $this->response->out($response);
        } else {
            $response =  array("status" => "1", "type" => "register", "message" => "User exists");
            $this->response->out($response);
        }
    }

    protected function getTask()
    {
        if (!$this->authUser->hasIdentity()) {
            $response =  array("status" => "1", "type" => "getTask", "message" => "You must logined");
            $this->response->out($response);
            return 0;
        }

        $userId = $this->authUser->getUserId();

        $db = new Db();
        $tasks = $db->getAllTask();
        $resolvedTask = $db->getResolveTaskByUser($userId);

        if ($tasks) {
            foreach ($tasks as $key => $task) {
                if ($resolvedTask) {
                    foreach ($resolvedTask as $resolveRask) {
                        if ($resolveRask['task'] == $task['id']) {
                            unset($tasks[$key]);
                        }
                    }
                } else {
                    $response =  array("status" => "0", "type" => "getTask", "message" => "Task", "task" => $task['task'], 'id' => $task['id']);
                    $this->response->out($response);
                    return 0;
                }

            }
        } else {
            $response =  array("status" => "2", "type" => "getTask", "message" => "Tasks don't exists");
            $this->response->out($response);
            return 0;
        }

        if ($tasks) {
            $response =  array("status" => "0", "type" => "getTask", "message" => "Task", "task" => $tasks[0]['task'], 'id' => $tasks[0]['id']);
            $this->response->out($response);
            return 0;
        } else {
            $response =  array("status" => "3", "type" => "getTask", "message" => "You resolved all tasks");
            $this->response->out($response);
            return 0;
        }
    }

    protected function resolveTask()
    {
        if (!$this->authUser->hasIdentity()) {
            $response =  array("status" => "4", "type" => "resolveTask", "message" => "You must logined");
            $this->response->out($response);
            return 0;
        }

        $userId = $this->authUser->getUserId();
        $db = new Db();

        $data = array(
            'id' => $_GET['id'],
            'answer' => $_GET['answer'],
        );

        $isResolved = $db->isResolvedTask($userId, $data['id']);
        $task = $db->findTaskById($data['id']);

        if ($task) {
            if (!$isResolved) {
                if ($task['response'] == $data['answer']) {
                    $resolved = $db->taskResolved($userId, $task['id']);
                    $response =  array("status" => "0", "type" => "resolveTask", "message" => "Right answer");
                    $this->response->out($response);
                    return 0;
                } else {
                    $response =  array("status" => "3", "type" => "resolveTask", "message" => "Wrong answer");
                    $this->response->out($response);
                    return 0;
                }
            } else {
                $response =  array("status" => "2", "type" => "resolveTask", "message" => "This task resolved you");
                $this->response->out($response);
                return 0;
            }
        } else {
            $response =  array("status" => "1", "type" => "resolveTask", "message" => "This task don't exits");
            $this->response->out($response);
            return 0;
        }
    }

	protected function logout()
	{
		$db = new Db();
		$db->logout();
        $response =  array("status" => "0", "type" => "logout", "message" => "You logout");
        $this->response->out($response);
	}
}

?>