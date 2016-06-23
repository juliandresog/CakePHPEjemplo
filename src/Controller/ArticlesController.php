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
use Cake\Utility\Security;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\Number;
use Cake\Utility\Text;
use Cake\I18n\Time;
use Cake\I18n\Date;

/**
 * Description of ArticlesController
 *
 * @author josorio
 */
class ArticlesController extends AppController {

    public $paginate = [
        'limit' => 30,
        'order' => [
            'Articles.title' => 'asc'
        ],
        'sortWhitelist' => [
            'Articles.title'
        ]
    ];

    /**
     * 
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Flash'); // Include the FlashComponent
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Math');
        $this->loadComponent('Paginator');
        $this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        if ($this->RequestHandler->accepts('html')) {
            // Execute code only if client accepts an HTML (text/html)
            // response.
        } elseif ($this->RequestHandler->accepts('xml')) {
            // Execute XML-only code
        }
        if ($this->RequestHandler->accepts(['xml', 'rss', 'atom'])) {
            // Executes if the client accepts any of the above: XML, RSS
            // or Atom.
        }

        //$this->log('Got here', 'debug');
        //$this->log('Got here', 'debug');
        Log::debug('Got here');
        Log::warning("Resultado: " . $this->Math->doComplexOperation(1, 4));
    }

    /**
     * Index method
     * @return \Cake\Network\Response|null
     */
    public function index() {
        try {
            $this->paginate();
        } catch (NotFoundException $e) {
            // Do something here like redirecting to first or last page.
            // $this->request->params['paging'] will give you required info.
        }
        //$articles = $this->Articles->find('all');
        //$this->set(compact('articles'));
        //--
        //$this->set('articles', $this->Articles->find('all'));
        //--
        //$this->set('articles', $this->Articles->listaSQLArticulos());
        //--
        //$tags = $this->paginate($this->Articles);
        //$this->set(compact('articles'));
        //$this->set('_serialize', ['articles']);
        //--
        //$articles = $this->Articles->find('all');
        //$this->set('articles', $this->paginate($articles));
        $articles = $this->paginate($this->Articles);
        //--
        $this->set(compact('articles'));
        $this->set('_serialize', ['articles']);
    }

    public function view($id = null) {
        //para ver el path
        // Holds /subdir/articles/edit/1?page=1
        //$this->$request->here;
        // Holds /subdir
        //$this->$request->base;
        // Holds /subdir/
        //$this->$request->webroot;
        //operacion real del view
        //$article = $this->Articles->get($id);
        //$this->set(compact('article'));

        $article = $this->Articles->findById($id)->first();
        if (empty($article)) {
            throw new NotFoundException(__('Article not found'));
        }
        $this->set('article', $article);
        $this->set('_serialize', ['article']);
    }

    public function view2($id = null) {

        //operacion real del view
        $article = $this->Articles->get($id);
        if ($this->request->is('requested')) {
            $this->response->body(json_encode($article));
            return $this->response;
        }
        $this->set('$article', $article);
    }

    /**
     * 
     * @return type
     */
    public function add() {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->data);
            // Added this line
            $article->user_id = $this->Auth->user('id');
            // You could also do the following
            //$newData = ['user_id' => $this->Auth->user('id')];
            //$article = $this->Articles->patchEntity($article, $newData);
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }

    

    /**
     * Edit
     * @param type $id
     * @return type
     */
    public function edit($id = null) {
        $article = $this->Articles->get($id);
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->data);
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }
        $this->set('article', $article);
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * 
     * @param type $user
     * @return boolean
     */
    public function isAuthorized($user) {
        return true;

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
    
    /**
     * 
     */
    public function add2() {
        try {
            Log::warning("===============================================================");
            $article = $this->Articles->nuevoArticulo();
            
            Log::warning("===============================================================");
            //demo funcional
            /* Log::info("Entra a enviar un correo");
              $email = new Email(); //'default');
              $email->transport('default');
              $email->from(['soporte@talentoorganizacional.com' => 'My Site']);
              $email->to(['julian.osorio@boos.com.co' => 'My website'])
              ->subject('About')
              ->send('My message a enviar con boos 2');

             */

            //inmediato
            //Email::deliver('julian.osorio@boos.com.co', 'Subject fast', 'Message fast', ['from' => 'soporte@talentoorganizacional.com']);
            //--
            //prueba security
            // Assuming key is stored somewhere it can be re-used for
            // decryption later.
            $value = 'Julian';
            $key = 'wt1U5MACWJFTXGenFoZoiLwQGrLgdbHA';
            $cipher = Security::encrypt($value, $key);
            Log::alert("Cifrado " . $cipher);
            $result = Security::decrypt($cipher, $key);
            Log::alert("Descifrado " . $result);
            // Using the application's salt value
            $sha1 = Security::hash('CakePHP Framework', 'sha1', true); //md5, sha1, sha256
            //CSRF
            $token = $this->request->param('_csrfToken');
            Log::alert("_csrfToken: " . $token);
            //File al folder
            $dir = new Folder('/tmp/');
            $files = $dir->find('.*\.txt');
            foreach ($files as $file) {
                $file = new File($dir->pwd() . DS . $file);
                $contents = $file->read();
                Log::alert("Contenido file: ".$contents);
                // $file->write('I am overwriting the contents of this file');
                // $file->append('I am adding to the bottom of this file.');
                // $file->delete(); // I am deleting this file
                $file->close(); // Be sure to close the file when you're done
            }
            //time
            // Returns '2014-04-12 12:22:30'
            $now = Time::now();
            Log::alert("Ahora: ".$now);
            //$now = Time::parse('2014-10-31');
            // Outputs 'Oct 31, 2014 12:00 AM' in en-US
            //echo $now->nice();
            Log::alert("Ahora nice: ".$now->nice());
        } catch (Exception $e) {
            Log::error("Resultado: " . $e->getMessage());
        }
    }

}
