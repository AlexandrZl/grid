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

        switch(true) {
            case $cmd == "start":
                $this->start();
                break;
            case $cmd == "runTask":
                $this->runTask();
                break;
            default:
                $this->another();
                break;
        }
	}

	protected function start()
	{
        $node = new Node();
        $node->create();
        $task = new Task();
        $task->create();

        $response =  array("status" => "Running");
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
