<?php namespace App\Services\Bitrix24\Models;

use App\Services\Bitrix24\Bitrix24;
use App\Services\Bitrix24\Exceptions\Bitrix24TaskNotCreatedException;
use Carbon\Carbon;

class Task extends Bitrix24
{

    protected $params = [
        "fields" => [
            "TITLE" => "",
            "DESCRIPTION" => "",
            "STATUS" => 2,
            "DEADLINE" => "",
            "ALLOW_CHANGE_DEADLINE" => "Y",
            "AUDITORS" => [1],
            "RESPONSIBLE_ID" => ""
        ]
    ];

    protected $created_task_id = false;


    public function __construct()
    {

    }


    /**
     * Create task in Bitrix24
     * @return int
     * @throws \App\Services\Bitrix24\Exceptions\Bitrix24TaskNotCreatedException
     */
    public function create(): int
    {
        $this->request('task.item.add', $this->params);

        if (isset($this->response->result)) {
            return (int) $this->response->result;
        } else {
            throw new Bitrix24TaskNotCreatedException(serialize($this->response));
        }
    }


    /**
     * Complete task in Bitrix24
     * @param $task_id
     * @return $this
     */
    public function complete($task_id)
    {
        $this->request('tasks.task.complete', ["taskId" => $task_id]);

        return $this;
    }


    public function setTitle(string $title): Task
    {
        $this->params["fields"]["TITLE"] = $title;
        return $this;
    }


    public function setDescription(string $description): Task
    {
        $this->params["fields"]["DESCRIPTION"] = $description;
        return $this;
    }
    

    public function setDeadline(Carbon $deadline): Task
    {
        $this->params["fields"]["DEADLINE"] = $deadline->format('Y-m-d\TH:i:s.uP T');
        return $this;
    }


    public function setAuditors(array $ids): Task
    {
        $this->params["fields"]["AUDITORS"] = $ids;
        return $this;
    }


    public function setResponsible(int $id): Task
    {
        $this->params["fields"]["RESPONSIBLE_ID"] = $id;
        return $this;
    }


    public function getCreatedTaskId(): int
    {
        return (int) $this->created_task_id;
    }




}