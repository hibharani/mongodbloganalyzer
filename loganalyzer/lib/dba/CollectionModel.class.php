<?php
require_once dirname(__FILE__).'/MongoDBConnection.class.php';

class CollectionModel
{
  protected $dbname;
  
  public function __construct($dbname)
  {
    $this->dbname = $dbname;
  }
  
  public function getCollections()
  {
    $collection_list = MongoDBConnection::getInstance()
                        ->getConnection()
                        ->selectDb($this->dbname)
                        ->listCollections();
    
    array_shift($collection_list);
    
    $collections = array();
    foreach($collection_list as $coll)
    {
      $parts = explode('.', $coll);
      $collections[$parts[1]] = $parts[1];
    }
    
    return $collections;
  }
}