<?php 

class App
{
	private $route;
    private $response;

	public function __construct()
	{
        $this->route = new Route();
        $this->response = new XMLResponse();
	}

	public function run()
    {
        $cmd = $this->route->getRoute();
        $params = $this->route->getParams();

        if($cmd !== 'start'){
            if(!$this->checkHash($params)){
                $response =  array("status" => "Error", 'ClientHash' => "You did not point a hash");
                $this->response->out($response);
                return false;
            }
        }

        switch(true) {
            case $cmd == "start":
                $this->start();
                break;
            case $cmd == "runTask":
                $this->runTask();
                break;
            case $cmd == "getTask":
                $this->getTask($params);
                break;
            case $cmd == "clientInfo":
                $this->clientInfo($params);
                break;
            default:
                $this->another();
                break;
        }
	}

    private function getTask($params){
        if($params && $params['task']){
            $task = new Task();
            $info = $task->findById($params['task']);
            if($info){
                $this->response->out($info);
            } else {
                $response =  array("status" => "Error", 'Task' => "You did not point a task id or it doesn't exist");
                $this->response->out($response);
                return false;
            }
        } else {
            $response =  array("status" => "Error", 'Task' => "You did not point a task id or it doesn't exist");
            $this->response->out($response);
            return false;
        }
    }

    private function clientInfo($params){
        if($params && $params['client']){
            $client = new Client();
            $info = $client->findById($params['client']);
            if($info){
                $this->response->out($info);
            } else {
                $response =  array("status" => "Error", 'Task' => "You did not point a client id or it doesn't exist");
                $this->response->out($response);
                return false;
            }
        } else {
            $response =  array("status" => "Error", 'Task' => "You did not point a client id or it doesn't exist");
            $this->response->out($response);
            return false;
        }
    }

    private function checkHash($params)
    {
        if($params && $params['hash']){
            $client = new Client();
            $result = $client->findByHash($params['hash']);
            return $result ? true : false;
        } else {
            return false;
        }
    }

	protected function start()
	{
        $node = new Node();
        $node->create();
        $task = new Task();
        $task->create();
        $client = new Client();
        $hash = $client->create();

        $response =  array("status" => "Running", 'ClientHash' => $hash);
        $this->response->out($response);
	}

    protected function another()
    {
        $response =  array("status" => "This is command doesn't exist");
        $this->response->out($response);
    }

    protected function createTask()
    {
        $task = new Task();
        $task->create();
    }

    protected function createNode()
    {
        $node = new Node();
        $node->create();
    }

    protected function runTask()
    {
        $task = new Task();
        $idTask = $task->findFree();

        $node = new Node();
        $idNode = $node->findFree();

        if($idNode['id'] && $idTask['id']){
            $node->runTask($idNode['id'], $idTask['id']);
            $task->inProgress($idTask['id']);
        }

    }
}
