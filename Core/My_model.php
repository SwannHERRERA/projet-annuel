<?php
 require_once BASEPATH . DIRECTORY_SEPARATOR ."conf.inc.php";
 abstract class My_model
 {
     protected $pdo;
     protected $_table;
     protected $table_primary_key = 'id';
     public function __construct()
     {
         try {
             //$pdo = new PDO("sqlite:./data.db");
             $this->pdo = new PDO(DBDRIVER.":host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPWD);
             //$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=ma_base;user=swann;password=root");
             $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         } catch (Exception $e) {
             die("Erreur SQL : ".$e->getMessage());
         }
     }

     /**
     * Renvoie toute la table
     * @return $result
     */
     public function get_all()
     {
         $query = $this->pdo->query("SELECT * FROM " . $this->_table);
         $result = $query->fetchAll();
         return $result;
     }

     /**
     * Renvoie les colonnes demander sur tous les lignes de la table
     * @param array $champs
     */
     public function get_columns($champs)
     {
         $str = '';
         foreach ($champs as $champ) {
             $str .= $champ . ',';
         }
         $str = substr($str, 0, -1);
         $query = $this->pdo->query('SELECT ' . $str . ' FROM ' . $this->_table);
         $result = $query->fetchAll();
         return $result;
     }
     /**
     * revoie un champ selon sa clé primaire
     *  @param $primary_key
     */
     public function get($primary_key)
     {
         $query = $this->pdo->prepare('SELECT * FROM ' . $this->_table .' WHERE ' . $this->table_primary_key . ' = :primary_key');
         $query = $this->pdo->execute([':primary_key' => $primary_key]);
         $result = $query->fetch();
         return $result;
     }
     /**
     * renvoie un tableau de champs selon les critères
     * @param array $criterions
     */
     public function get_many_by($criterions)
     {
         //SELECT * FROM table WHERE critère
         $query = $this->pdo->query('SELECT * FROM ' . $this->_table .' WHERE ' . $criterions);
         $result = $query->fetchAll();
         return $result;
     }
     /**
     * renvoie un champ selon les critères (le premier)
     * @param array $criterions
     * @return $result
     */
     public function get_by($criterions)
     {
         $result = $this->get_many_by($criterions);
         return $result[0];
     }

     public function get_columns_where($champs, $criterions, $logic = null)
     {
         //return SELECT champs FROM table WHERE criterions
         // logic = ['AND', 'OR', 'AND']
         if ($logic ==  null) {
             $logic = [];
         }
         $logic = array_push($logic, '');
         $str_column = '';
         foreach ($champs as $champ) {
             $str_column .= $champ . ',';
         }
         $str_column = substr($str_column, 0, -1);
         $str_constraint= '';
         $i = 0;
         foreach ($criterions as $key => $criterion) {
             $str_constraint .= $key . ' in (';
             if (is_array($criterion)) {
                 foreach ($criterion as $value) {
                     if (is_string($value)) {
                         $str_constraint .= '"' . $value . '",';
                     }
                 }
                 $str_constraint = substr($str_constraint, 0, -1);
             } else {
                 if (is_string($criterion)) {
                     $str_constraint .= '"' . $criterion . '")';
                 }
             }
             if (empty($logic)) {
                 $str_constraint .= ' '. $logic[$i] . ' ';
             }
             $i++;
         }//echo 'SELECT ' . $str_column . ' FROM ' . $this->_table . ' WHERE ' . $str_constraint;
         $query = $this->pdo->query('SELECT ' . $str_column . ' FROM ' . $this->_table . ' WHERE ' . $str_constraint);
         $result = $query->fetchAll();
         return $result;
     }
     public function is_unique($field)
     {
         $queryPrepared = $this->pdo->prepare("SELECT $this->table_primary_key FROM $this->_table WHERE $field=:field LIMIT 1");
         $queryPrepared->execute([":field"=>$_POST[$field]]);
         $result = $queryPrepared->fetch();
         return $result;
     }
     /* public function insert($champs = NULL, $values){
        if ($champs === NULL) {
          //INSERT INTO table VALUES ...
          $query = $this->pdo->query('INSERT INTO ' . $this->_table . $values);
        }else {
          // INSERT INTO table (champs) VALUES ...
          $query = $this->pdo->query('INSERT INTO ' . $this->_table . $champs . $values);

        }
      } */
     public function update($champs, $primary_key)
     {
     }
     public function delete($champs, $primary_key)
     {
     }
 }
