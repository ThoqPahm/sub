<?php
namespace App\Controller\Member;

use App\Controller\Member\AppMemberController;

/**
 * @property \App\Model\Table\PlatformActionsTable $PlatformActions
 */
class PlatformActionsController extends AppMemberController
{
    public function index()
    {
        $userId = $this->Auth->user('id');

        $customActions = $this->PlatformActions->find('all')
            ->contain(['Platforms'])
            ->where(['PlatformActions.user_id' => $userId, 'PlatformActions.is_system' => 0, 'PlatformActions.is_active' => 1]);

        $platforms = $this->PlatformActions->Platforms->findAvailableForUser($this->PlatformActions->Platforms->find(), ['user_id' => $userId]);

        $this->set(compact('customActions', 'platforms'));
    }

    public function add()
    {
        $action = $this->PlatformActions->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = $this->Auth->user('id');
            $data['is_system'] = 0;
            $data['is_active'] = 1;

            $action = $this->PlatformActions->patchEntity($action, $data);
            if ($this->PlatformActions->save($action)) {
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode([
                            'status' => 'success',
                            'data' => $action
                        ]));
                }
                $this->Flash->success(__('The action has been saved.'));
                return $this->redirect($this->referer());
            }

            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => 'error',
                        'message' => 'Could not save action',
                        'errors' => $action->getErrors()
                    ]));
            }
            $this->Flash->error(__('The action could not be saved. Please, try again.'));
        }
        $this->set(compact('action'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $action = $this->PlatformActions->get($id);

        if ($action->user_id !== $this->Auth->user('id')) {
            $this->Flash->error(__('You are not authorized to delete this action.'));
            return $this->redirect($this->referer());
        }

        if ($this->PlatformActions->delete($action)) {
            $this->Flash->success(__('The action has been deleted.'));
        } else {
            $this->Flash->error(__('The action could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }

    public function getAvailableForPlatform($platformId)
    {
        $this->request->allowMethod(['ajax', 'get']);
        $userId = $this->Auth->user('id');

        $actions = $this->PlatformActions->findAvailableForPlatform($this->PlatformActions->find(), [
            'platform_id' => $platformId,
            'user_id' => $userId
        ])
            ->select(['id', 'name', 'is_system'])
            ->order(['is_system' => 'DESC', 'name' => 'ASC'])
            ->toArray();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'success',
                'data' => $actions
            ]));
    }
}
