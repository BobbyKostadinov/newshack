<?php
namespace Newshack\Api;

class Client
{
   private $_apiKey = 'saghM7yCO1hJj4yQBZ14EngYGsVnsUJh';
   private $_host = 'http://data.bbc.co.uk/v1/bbcrd-newslabs';
  
  public function findConceptCoOccurances($uri, $type, $limit = 500, $timePeriod = '- 5 MONTH')
 {
	//In an ideal world - cache
	$url = 	$this->_host
		 . '/concepts/co-occurrences?uri='
		 . $uri
	         . '&type=http://dbpedia.org/ontology/'
		 . $type 
		 . '&limit=' 
		 . $limit 
		 . '&after=' . '2014-02-01' //date('Y-m-d', (strtotime(date('Y-m-d') . $timePeriod)))
		 . '&apikey=' . $this->_apiKey;

      $config = array(
        'adapter'   => 'Zend_Http_Client_Adapter_Curl',
      );
    $client = new \Zend_Http_Client($url, $config);
    $response = $client->request('GET');
    return $response->getBody();
 }
 public function getMatchingCreativeWorks($concept1, $concept2, $limit = 4)
 { 
	//In an ideal world - cache
        $url =  $this->_host
                 . '/creative-works?'
                 . '&tag=' . $concept1
                 . '&tag=' . $concept2	
                 . '&limit=' . $limit
		 . '&tagtop=and'
		 .  '&after=' . '2014-02-01' //date specific
                 . '&apikey=' . $this->_apiKey;
      $config = array(
        'adapter'   => 'Zend_Http_Client_Adapter_Curl',
      );// echo $url; 
    $client = new \Zend_Http_Client($url, $config);
    $response = $client->request('GET');
    return $response->getBody();
 }  
}
