<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\UnauthorizedException;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Model\Table\ApiKeysTable $ApiKeys
 * @property \App\Model\Table\UnlockLogsTable $UnlockLogs
 */
class UnlockApiController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Links');
        $this->loadModel('ApiKeys');
        $this->loadModel('UnlockLogs');
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getTasks', 'completeTask', 'verifyCompletion']);

        if (isset($this->Csrf)) {
            $this->getEventManager()->off($this->Csrf);
        }
        if (isset($this->Security)) {
            $this->getEventManager()->off($this->Security);
        }

        $this->authenticateApi();
    }

    protected function authenticateApi()
    {
        if ($this->request->is('options')) {
            return true;
        }

        // Allow bypass for now for testing
        return true;
    }

    public function getTasks($alias = null)
    {
        $this->request->allowMethod(['get']);

        $link = $this->Links->find()
            ->contain(['UnlockTasks' => ['Platforms', 'PlatformActions']])
            ->where(['alias' => $alias])
            ->first();

        if (!$link) {
            throw new NotFoundException('Link not found');
        }

        $tasks = [];
        foreach ($link->unlock_tasks as $task) {
            $tasks[] = [
                'id' => $task->id,
                'step' => $task->step_order,
                'platform' => $task->platform->name,
                'platform_icon' => $task->platform->icon,
                'action' => $task->platform_action->name,
                'url' => $task->platform_url,
                'is_required' => $task->is_required
            ];
        }

        $this->set([
            'status' => 'success',
            'data' => [
                'alias' => $link->alias,
                'title' => $link->title,
                'tasks' => $tasks,
                'settings' => [
                    'redirect_mode' => $link->redirect_after_unlock
                ]
            ],
            '_serialize' => ['status', 'data']
        ]);
    }

    public function completeTask($alias = null, $taskId = null)
    {
        $this->request->allowMethod(['post']);

        $this->set([
            'status' => 'success',
            'message' => 'Task completed',
            '_serialize' => ['status', 'message']
        ]);
    }

    public function verifyCompletion($alias = null)
    {
        $this->request->allowMethod(['post']);

        $link = $this->Links->find()
            ->where(['alias' => $alias])
            ->first();

        if (!$link) {
            throw new NotFoundException('Link not found');
        }

        $this->set([
            'status' => 'success',
            'all_completed' => true,
            'redirect_url' => $link->url,
            '_serialize' => ['status', 'all_completed', 'redirect_url']
        ]);
    }
}
