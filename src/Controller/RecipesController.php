<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;

/**
 * Description of RecipesController
 * RESTFul de Recipies
 *
 * @author josorio
 */
class RecipesController extends AppController {

    /**
     * 
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        //$this->loadComponent('Security');
        Log::alert("Recipes :: Inicializado el controller");
        //$this->Auth->allow(['index', 'add']);
    }

    /**
     * This example would disable all security checks for the edit action.
     * @param Event $event
     */
    public function beforeFilter(Event $event) {
        //$this->Security->config('unlockedActions', ['edit', 'add']);
        Log::alert("Recipes :: before");
    }

    /**
     * GET lista
     */
    public function index() {
        Log::alert("Recipes :: index");
        $recipes = $this->Recipes->find('all');
        $this->set([
            'recipes' => $recipes,
            '_serialize' => ['recipes']
        ]);
    }

    /**
     * GET registro
     * @param type $id
     */
    public function view($id) {
        Log::alert("Recipes :: view");
        $recipe = $this->Recipes->get($id);
        $this->set([
            'recipe' => $recipe,
            '_serialize' => ['recipe']
        ]);
    }

    /**
     * POST
     */
    public function add() {
        try {
            Log::alert("Recipes :: add");
            $recipe = $this->Recipes->newEntity($this->request->data);
            $success = false;
            if ($this->Recipes->save($recipe)) {
                $message = 'Saved ok';
                $success = true;
            } else {
                $message = 'Error in saved';
                $success = false;
            }
            $this->set([
                'message' => $message,
                'recipe' => $recipe,
                'success' => $success,
                '_serialize' => ['message', 'recipe', 'success']
            ]);
        }catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

    /**
     * PUT/PATCH
     * @param type $id
     */
    public function edit($id) {
        Log::alert("Recipes :: edit");
        $recipe = $this->Recipes->get($id);
        $success = false;
        if ($this->request->is(['post', 'put'])) {
            $recipe = $this->Recipes->patchEntity($recipe, $this->request->data);
            if ($this->Recipes->save($recipe)) {
                $message = 'Saved';
                $success = true;
            } else {
                $message = 'Error';
                $success = false;
            }
        }
        $this->set([
            'message' => $message,
            'success' => $success,
            '_serialize' => ['message', 'success']
        ]);
    }

    /**
     * DELETE
     * @param type $id
     */
    public function delete($id) {
        Log::alert("Recipes :: delete");
        $recipe = $this->Recipes->get($id);
        $message = 'Deleted';
        $success = true;
        if (!$this->Recipes->delete($recipe)) {
            $message = 'Error';
            $success = false;
        }
        $this->set([
            'message' => $message,
            'success' => $success,
            '_serialize' => ['message', 'success']
        ]);
    }

    /**
     * 
     * @param type $user
     * @return boolean
     */
    public function isAuthorized($user) {
        return parent::isAuthorized($user);
        //Log::alert("Recipes :: authorized");
        //return true;

        /*
          // All registered users can add articles
          if ($this->request->action === 'add') {
          return true;
          }
          // The owner of an article can edit and delete it
          if (in_array($this->request->action, ['edit', 'delete'])) {
          $articleId = (int) $this->request->params['pass'][0];
          if ($this->Articles->isOwnedBy($articleId, $user['id'])) {
          return true;
          }
          }
          return parent::isAuthorized($user);

         */
    }
}
