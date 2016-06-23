<?php

namespace App\Model\Table;

use App\Model\Entity\Article;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Articles Model
 *
 */
class ArticlesTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('articles');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        //$validator->allowEmpty('title');
        //$validator->allowEmpty('body');
        $validator
                ->notEmpty('title')
                ->requirePresence('title')
                ->notEmpty('body')
                ->requirePresence('body');

        return $validator;
    }

    public function isOwnedBy($articleId, $userId) {
        return $this->exists(['id' => $articleId, 'user_id' => $userId]);
    }

    public function listaSQLArticulos() {
        //$connection = ConnectionManager::get('default');
        $connection = $this->connection();
        $results = $connection->execute('SELECT * FROM articles')->fetchAll('assoc');
        Log::warning("Resultado: " . $results);
        return $results;
    }

    public function nuevoArticulo() {
        try {
            $connection = ConnectionManager::get('default');
            //$connection = $this->connection();
            $connection->insert('articles', [
                'title' => 'A New Article dos',
                'created' => new \DateTime('now')
                    ], ['created' => 'datetime']);
        } catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

    /**
     * Demos del orm
     */
    public function demosORM() {
        try {
            /*
              //habiendo usado convensiones puedo.
              $articles = TableRegistry::get('Articles');
              $query = $articles->find();
              foreach ($query as $row) {
              //echo $row->title;
              Log::warning("TITULO: ".$row->title);
              }
             */
            $connection = ConnectionManager::get('default');
            //Usando query manual
            $results = $connection
                    ->execute('SELECT * FROM articles WHERE id = :id', ['id' => 1])
                    ->fetchAll('assoc');
            foreach ($results as $row) {
                //echo $row->title;
                Log::warning("TITULO: " . print_r($row));
                //print_r($row);
            }

            //$dStart = new \DateTime($now);
            //usando query builder
            $results = $connection
                    ->newQuery()
                    ->select('*')
                    ->from('articles')
                    ->where(['created >' => new \DateTime('1 day ago'), ['created' => 'datetime']])
                    ->order(['title' => 'DESC'])
                    ->execute()
                    ->fetchAll('assoc');
            foreach ($results as $row) {
                //echo $row->title;
                Log::warning("TITULO2: " . print_r($row));
                //print_r($row);
            }
        } catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

}
