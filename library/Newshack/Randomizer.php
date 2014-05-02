<?php
namespace Newshack;

class Randomizer
{
   const CONCEPT_TYPE_PERSON = 'Person';
   const CONCEPT_TYPE_PLACE = 'Place';
   const CONCEPT_TYPE_ORGANISATION = 'Organisation';

   protected $_apiClient;
   
    public function __construct(Api\Client $apiClient = null)
    {
        $this->_apiClient = $apiClient;
    }

     public function fetchRandomConcepts($concept)
     {
	$conceptTypes = $this->getRandomConceptTypes();
        $data = array();
	foreach ($conceptTypes as $conceptType) {
	    $data[] = json_decode($this->_apiClient->findConceptCoOccurances($concept, $conceptType), true);
        }
        $data = $this->_cleanupResults($data);
        $topPart = round(count($data[0])/6);
        $random[] = $data[0][rand(0, $topPart)];
	$random[] = $data[0][rand($topPart + 1, $topPart*2)];
	$topPart2 = round(count($data[1])/6);
	$random[] = $data[1][rand(0, $topPart2)];
	$last  = $data[1][count($data[1]) - 1];
	$articlesResponse = $this->_apiClient->getMatchingCreativeWorks($concept, $last['thing']);
	$articles = $this->_cleanupArticles($articlesResponse);
        $last['articles'] = $articles;
	$last['correct'] = true;
 	$random[] = $last;
	shuffle($random);
	return ($random);
    }	
 
  private function _cleanupArticles($articlesResponse)
  {
	$cleanArticles = array();
	$articles = json_decode($articlesResponse, true);
	foreach ($articles['@graph'] as $row) {//  echo '<pre>'; var_dump($row['subject']); echo '</pre>';
		$temp = array();
		$temp['headline'] = $row['title'];
		$temp['publication'] = $row['subject'];
		$temp['url'] = $row['primaryContentOf'];
		$cleanArticles[] = $temp;
	}
	return $cleanArticles;
  }

   protected function getRandomConceptTypes()
   {
	$types = array(self::CONCEPT_TYPE_PERSON, self::CONCEPT_TYPE_PLACE, self::CONCEPT_TYPE_ORGANISATION);
	shuffle($types);
	unset ($types[rand(0,count($types))]);
	return $types;
   }

   private function _cleanupResults($data) 
  { 
    $cleanResult = array();
    foreach ($data as $dataIndex => $dataRow) {
	foreach ($dataRow['co-occurrences'] as $rowIndex => $row) {
		if (isset($row['img'])) {
			$cleanResult[$dataIndex][] = $row;
		}
	}
     } 
    return $cleanResult;
  }
}
