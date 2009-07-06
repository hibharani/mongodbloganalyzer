<?php
require_once dirname(__FILE__).'/MongoDBConnection.class.php';

class MongoLogReader
{
  protected $dbname;
  protected $collection; 
  
  public function __construct($dbname, $collection)
  {
    $this->dbname = $dbname;
    $this->collection = $collection;
  }
  
  public function retrieveLogs($query)
  {
    return MongoDBConnection::getInstance()
            ->getConnection()
            ->selectDB($this->dbname)
            ->selectCollection($this->collection)
            ->find($query);
  }
}