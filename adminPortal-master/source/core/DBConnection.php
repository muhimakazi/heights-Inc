<?php 

    class DBConnection {

        private static $_instance = null;
        private $_pdo,
                $_query,
                $_error = false,
                $_results,
                $_count = 0;
    
        private function __construct() {
            try {
                $this->_pdo = new PDO('mysql:host=' . Config::get('dbc/host') . ';dbname=' . Config::get('dbc/db'), Config::get('dbc/username'), Config::get('dbc/password'));
            } catch(PDOException $e) {
                die($e->getMessage());
            }
        }
    
        public static function getInstance() {
            if(!isset(self::$_instance)) {
                self::$_instance = new DBConnection();
            }
            return self::$_instance;
        }
        
        public function query($sql, $params = array()) {
            $this->_error = false;
    
            if($this->_query = $this->_pdo->prepare($sql)) {
                $x = 1;
                if(count($params)) {
                    foreach($params as $param) {
                        $this->_query->bindValue($x, $param);
                        $x++;
                    }
                }
    
                if($this->_query->execute()) {
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    $this->_count = $this->_query->rowCount();
                } else {
                    $this->_error = true;
                }
            }
    
            return $this;
        }

        public function results() {
            return $this->_results;
        }
    
        public function first() {
            $data = $this->results();
            return $data[0];
        }
    
        public function count() {
            return $this->_count;
        }
    
        public function error() {
            return $this->_error;
        }
    
    }

?>