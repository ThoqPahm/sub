<?php
namespace App\Controller\Member;

use App\Controller\Member\AppMemberController;

/**
 * @property \App\Model\Table\PlatformsTable $Platforms
 */
class PlatformsController extends AppMemberController
{
    public function index()
    {
        $userId = $this->Auth->user('id');

        $systemPlatforms = $this->Platforms->find('all')
            ->where(['is_system' => 1, 'is_active' => 1]);

        $customPlatforms = $this->Platforms->find('all')
            ->where(['user_id' => $userId, 'is_system' => 0, 'is_active' => 1]);

        $this->set(compact('systemPlatforms', 'customPlatforms'));
    }

    public function add()
    {
        $platform = $this->Platforms->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = $this->Auth->user('id');
            $data['is_system'] = 0;
            $data['is_active'] = 1;

            $platform = $this->Platforms->patchEntity($platform, $data);
            if ($this->Platforms->save($platform)) {
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode([
                            'status' => 'success',
                            'data' => $platform
                        ]));
                }
                $this->Flash->success(__('The platform has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => 'error',
                        'message' => 'Could not save platform',
                        'errors' => $platform->getErrors()
                    ]));
            }
            $this->Flash->error(__('The platform could not be saved. Please, try again.'));
        }
        $this->set(compact('platform'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $platform = $this->Platforms->get($id);

        if ($platform->user_id !== $this->Auth->user('id')) {
            $this->Flash->error(__('You are not authorized to delete this platform.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->Platforms->delete($platform)) {
            $this->Flash->success(__('The platform has been deleted.'));
        } else {
            $this->Flash->error(__('The platform could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getAvailableForUser()
    {
        $this->request->allowMethod(['ajax', 'get']);
        $userId = $this->Auth->user('id');

        $platforms = $this->Platforms->findAvailableForUser($this->Platforms->find(), ['user_id' => $userId])
            ->select(['id', 'name', 'is_system'])
            ->order(['is_system' => 'DESC', 'name' => 'ASC'])
            ->toArray();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'success',
                'data' => $platforms
            ]));
    }
}
