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
use Cake\I18n\Date;

//use App\Model\Entity\Article; //para instanciar la entidad directamente.

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
        
        //When a new entity is saved the created and modified fields will be set to the current time.
        //When an entity is updated, the modified field is set to the current time.
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
                    ->where(['created >' => new Date('1 day ago'), ['created' => 'datetime']])
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

    /**
     * Demos del orm
     */
    public function demosORMTransaction() {
        try {
            $conn = ConnectionManager::get('default');
            //modo 1
            $conn->begin();
            $conn->execute('UPDATE posts SET published = ? WHERE id = ?', [true, 2]);
            $conn->execute('UPDATE posts SET published = ? WHERE id = ?', [false, 4]);
            $conn->commit();
            //modo 2
            $conn->transactional(function ($conn) {
                $conn->execute('UPDATE posts SET published = ? WHERE id = ?', [true, 2]);
                $conn->execute('UPDATE posts SET published = ? WHERE id = ?', [false, 4]);
            });
        } catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

    /**
     * Demos del orm
     */
    public function demosORMStatement() {
        try {
            $conn = ConnectionManager::get('default');
            $stmt = $conn->prepare(
                    'SELECT * FROM posts WHERE published = :published AND created > :created'
            );
            // Bind multiple values
            $stmt->bind(
                    ['published' => true, 'created' => new Date('2013-01-01')], ['published' => 'boolean', 'created' => 'date']
            );
            // Bind a single value
            $stmt->bindValue('published', true, 'boolean');
            $stmt->bindValue('created', new Date('2013-01-01'), 'date');
            //execute
            $stmt->execute();
            // Read one row.
            //$row = $stmt->fetch('assoc');
            // Read all rows.
            $rows = $stmt->fetchAll('assoc');

            $rowCount = count($stmt);
            $rowCount = $stmt->rowCount();
            Log::warning("# de rows: " . $rowCount);

            // Read rows through iteration.
            foreach ($rows as $row) {
                // Do work
                //Log::warning("TITULO3: " . print_r($row));
                Log::warning("TITULO3: " . implode(" ", $row));
                //Log::warning("TITULO3: " . $row['created']);
                Log::warning("TITULO3: " . $row['id'] . " : " . $row['published'] . " : " . $row['created'] . " : " . $row['modified'] . " ! ");
            }

            $code = $stmt->errorCode();
            $info = $stmt->errorInfo();
            Log::warning("Error code: " . $code);
            Log::warning("Error info: " . implode(" ", $info));
        } catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

    /**
     * Demos del orm
     */
    public function demosORMSQLBuilder() {
        try {
            $articles = TableRegistry::get('Articles');
            // Use the combine() method from the collections library
            // This is equivalent to find('list')
            $keyValueList = $articles->find()->combine('id', 'title');
            // An advanced example
            $results = $articles->find()
                    ->where(['id >' => 1])
                    ->order(['title' => 'DESC'])
                    ->map(function ($row) { // map() is a collection method, it executes the query
                        $row->trimmedTitle = trim($row->title);
                        return $row;
                    })
                    ->combine('id', 'trimmedTitle') // combine() is another collection method
                    ->toArray(); // Also a collections library method
            foreach ($results as $id => $trimmedTitle) {
                echo "$id : $trimmedTitle ; ";
            }
        } catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

    /**
     * Demos del orm
     */
    public function demosORMSQLBuilder2() {
        try {
            $articles = TableRegistry::get('Articles');
            //$query = $articles->find();
            //$query->select(['id', 'title', 'body']);
            // Fetch rows 50 to 100
            $query = $articles->find()
                    ->order(['title' => 'ASC', 'id' => 'ASC'])
                    ->limit(5)
                    ->page(2);
            foreach ($query as $row) {
                //debug($row->title);
                Log::warning($row->title);
            }
        } catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

    /**
     * Demos del orm
     */
    public function demosORMSQLFunctions() {
        try {
            $articles = TableRegistry::get('Articles');
            // Results in SELECT COUNT(*) count FROM ...
            $query = $articles->find();
            $query->select(['count' => $query->func()->count('*')]);
            //tambien sum, avg, min, max count, concat, coalesce, dateDiff, now, extract, dateAdd, dayOfWeek
            Log::warning($query->first());

            //instanciar f1
            $articleM = new Article([
                'id' => 1,
                'title' => 'New Article',
                'created' => new \DateTime('now')
            ]);
            //instanciar f2
            /* $articleM = TableRegistry::get('Articles')->newEntity();
              $articleM = TableRegistry::get('Articles')->newEntity([
              'id' => 1,
              'title' => 'New Article',
              'created' => new DateTime('now')
              ]); */
            $articleM->title = 'This is my first post.';
            echo $articleM->title;
            $articleM->set('title', 'This is my first post 2');
            echo $articleM->get('title');
            // See if the title has been modified.
            $articleM->dirty('title');
        } catch (Exception $e) {
            Log::error("DB Exception: " . $e->getMessage());
        }
    }

}
