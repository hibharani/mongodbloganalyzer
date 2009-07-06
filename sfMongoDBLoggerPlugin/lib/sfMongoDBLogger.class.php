<?php

class sfMongoDBLogger extends sfLogger
{
  protected
    $type            = 'symfony',
    $db_name         = 'debuglogs',
    $connection      = null,
    $collection      = null;

  /**
   * Initializes this logger.
   *
   * Available options:
   *
   * - file:        The file path or a php wrapper to log messages
   *                You can use any support php wrapper. To write logs to the Apache error log, use php://stderr
   * - format:      The log line format (default to %time% %type% [%priority%] %message%%EOL%)
   * - time_format: The log time strftime format (default to %b %d %H:%M:%S)
   * - dir_mode:    The mode to use when creating a directory (default to 0777)
   * - file_mode:   The mode to use when creating a file (default to 0666)
   *
   * @param  sfEventDispatcher $dispatcher  A sfEventDispatcher instance
   * @param  array             $options     An array of options.
   *
   * @return Boolean      true, if initialization completes successfully, otherwise false.
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    $this->host = isset($options['host']) ? $options['host'] : Mongo::DEFAULT_HOST;
    $this->port = isset($options['port']) ? $options['port'] : Mongo::DEFAULT_PORT;

    if (isset($options['type']))
    {
      $this->type = $options['type'];
    }
    
    if (isset($options['db_name']))
    {
      $this->db_name = $options['db_name'];
    }
    
    $this->connection = MongoDBConnection::getInstance()->getConnection();
    $this->collection = $this->connection->selectDB($this->db_name)->selectCollection(date('Y-m-d'));

    return parent::initialize($dispatcher, $options);
  }

  /**
   * Logs a message.
   *
   * @param string $message   Message
   * @param string $priority  Message priority
   */
  protected function doLog($message, $priority)
  { 
    $log = array(
      'type'     => $this->type,
      'message'  => $message,
      'time'     => time(),
      'priority' => $this->getPriority($priority)
    );
    
    $this->collection->insert($log);
  }

  /**
   * Returns the priority string to use in log messages.
   *
   * @param  string $priority The priority constant
   *
   * @return string The priority to use in log messages
   */
  protected function getPriority($priority)
  {
    return sfLogger::getPriorityName($priority);
  }

  /**
   * Executes the shutdown method.
   */
  public function shutdown()
  {
    $this->connection->close();
  }
}
  