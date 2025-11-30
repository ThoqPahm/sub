<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class UnlockController extends FrontController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha');
        $this->loadModel('Links');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('front');
        $this->Auth->allow(['gateway']);
    }

    public function gateway($alias = null)
    {
        if (!$alias) {
            throw new NotFoundException(__('404 Not Found'));
        }

        $link = $this->Links->find()
            ->where(['alias' => $alias, 'status <>' => 3])
            ->first();

        if (!$link) {
            throw new NotFoundException(__('404 Not Found'));
        }

        // If not sub2unlock mode, redirect back to normal view
        if ($link->unlock_mode != 1) {
            return $this->redirect(['controller' => 'Links', 'action' => 'view', $alias]);
        }

        if ($this->request->is('post')) {
            // Verify Captcha
            // Temporarily bypassed as per plan
            /*
            if (!$this->Captcha->verify($this->request->getData())) {
                $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));
                return $this->redirect(['action' => 'gateway', $alias]);
            }
            */

            // Redirect to WordPress site
            // TODO: Get WordPress URL from config or user settings
            $wordpressUrl = "http://localhost/wordpress/unlock/" . $alias;

            // For now, redirect to google for testing if WP not set
            // return $this->redirect($wordpressUrl);

            // Or maybe just redirect to the original URL for now to show flow?
            // No, the flow is Gateway -> WordPress -> Original.
            // I'll assume localhost/wordpress for now.
            return $this->redirect($wordpressUrl);
        }

        $this->set(compact('link'));
    }
}
