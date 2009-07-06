<?php

class LogAnalyzer 
{
  protected $dispatcher;
  protected $maxPerPage = 20;
  
  public function __construct() 
  { 
    $this->dispatcher = new sfEventDispatcher(); 
    $this->dispatcher->connect('application.load_controller', array($this, 'loadController'));
    // $this->dispatcher->connect('application.view', array($this, 'loadController'));
  }
  
  public function run() 
  { 
    $request = new sfWebRequest($this->dispatcher); 
    $handler = new sfRequestHandler($this->dispatcher); 
    $response = $handler->handle($request); 
    return $response; 
  }
  
  public function loadController(sfEvent $event) 
  { 
    $event->setReturnValue(array(array($this, 'execute'), array($this->dispatcher, $event['request']))); 
    return true; 
  }
  
  public function execute($dispatcher, $request)
  {
    $response = new sfWebResponse($dispatcher); 
    $response->setContent($this->render($this->getTemplateValues($request))); 
    return $response;
  }
  
  protected function render($values)
  {
    extract($values);
    ob_start();
    ob_implicit_flush(0);
    require(ROOT_PATH . '/apps/template.php');
    return ob_get_clean();
  }
  
  protected function getTemplateValues($request)
  {
    $values = array();
    
    $values['sf_request'] = $request;
    
    $values['collections'] = $this->getCollections();
    
    $values['priorities'] = $this->getPriorities();
    
    $values['cursor'] = $this->getLogs($request);
    $values['pageNumber'] = $request->getParameter('page', 1);    
    $values['cursor']->skip(($values['pageNumber'] - 1) * $this->maxPerPage)->limit($this->maxPerPage);
    $values['hasMore'] = $values['cursor']->count() > ($this->maxPerPage * $values['pageNumber']);
    $values['filterParams'] = $this->buildFilterParams($request);
    
    return $values;
  }
  
  protected function buildFilterParams($request)
  {
    $filter = array();
    
    if($filterby = $request->getParameter('filterby'))
    {
      $filter[] = sprintf('filterby=%s', $filterby);
    }
    
    if($priority = $request->getParameter('priority'))
    {
      $filter[] = sprintf('priority=%s', $priority);
    }
    
    if($collection = $request->getParameter('collection'))
    {
      $filter[] = sprintf('collection=%s', $collection);
    }
    
    return implode('&', $filter);
  }
  
  protected function getCollections()
  {
    $c_model = new CollectionModel('debuglogs');
    return $c_model->getCollections();
  }
  
  protected function getLogs($request)
  {
    $log_reader = new MongoLogReader('debuglogs', $request->getParameter('collection', date('Y-m-d')));
    return $log_reader->retrieveLogs($this->buildQuery($request));
  }
  
  protected function buildQuery($request)
  {
    $query = array();

    if($filterby = $request->getParameter('filterby'))
    {
      $query = array('message' => new MongoRegex("/.*".$filterby.".*/i"));
    }
    
    if($priority = $request->getParameter('priority'))
    {
      if($priority != 'all') $query['priority'] = $priority;
    }
    
    return $query;
  }
  
  protected function getPriorities()
  {
    return array('all' => '------', 'error' => 'Error', 'warning' => 'Warning', 
                                        'notice' => 'Notice', 'info' => 'Info', 'debug' => 'Debug');
  }
}