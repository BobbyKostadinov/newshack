<?php

class RandomController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $round = $this->getRequest()->getParam('round', 1);
	//In an ideal world - get those from currently viewed article;
	switch ($round) {
		case 1:
			$concept = 'http://dbpedia.org/resource/Nigel_Farage';
			$label = 'Nigel Farage';
			break;
		case 2:
			$concept = 'http://dbpedia.org/resource/Kim_Jong-un';
			$label = 'Kin Jong-un';	
			break;
		case 3:
			$concept = 'http://dbpedia.org/resource/Boris_Johnson';
			$label = 'Boris Johnson';
			break;		
		case 4:
			$concept = 'http://dbpedia.org/resource/Hillary_Rodham_Clinton';
			$label = 'Hillary Clinton';
			break;
		case 5:
			$concept = 'http://dbpedia.org/resource/Dalai_Lama';
			$label = 'the Dalai Lama';
			break;
		default:
			$label = 'Nigel Farage';
			$concept = 'http://dbpedia.org/resource/Nigel_Farage';
	}
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
	$randomizer = new \Newshack\Randomizer(new \Newshack\Api\Client());
        echo json_encode(array(
		'question' => array(
			'label' => $label,
			'thing' => $concept //'http://dbpedia.org/resource/Nigel_Farage' 
		),
		'answers' => $randomizer->fetchRandomConcepts($concept)
		)
	);
	exit;
    }

  public function testAction()
  {
   $randomizer = new \Newshack\Api\Client();
   
	die($randomizer->getMatchingCreativeWorks('http://dbpedia.org/resource/Dalai_Lama', 'http://dbpedia.org/resource/Boris_Johnson'));
  }
}
