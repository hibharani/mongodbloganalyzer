<?php

class MongoDBConnection
{
  protected static $instance;
  protected $connection = null;
  protected $host;
  protected $port;
  
  protected function __construct()
  {}
  
  public static function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new MongoDBConnection();
    }

    return self::$instance;
  }
  
  public function getConnection()
  {
    if(!$this->connection)
    {
      $this->connection = new Mongo();
    }
    
    return $this->connection;
  }
}