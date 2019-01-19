<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Zillow extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('zillow/Zillow_lib');

		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

	}

	public function index()
	{
		$result = [];
		$error = '';

		if ($addressInp = $this->input->post('address')) {

			if (!$result = $this->cache->get($addressInp)) {

				$zillow = new Zillow_Lib();
				$address = explode(',', $addressInp);
				
				if (count($address) < 2) {

					return $this->output
						->set_content_type('application/json')
						->set_output(json_encode(['error' => 'Invalid address'])
					);
				}

				$searchRes = $zillow->getFirstSearchResult(urlencode(trim($address[0])), trim($address[1]));

				if (!$searchRes) {

					return $this->output
						->set_content_type('application/json')
						->set_output(json_encode(['error' => 'Search result is empty'])
					);
				}

				$price = number_format($searchRes->zestimate->amount->__toString());

				$result = [
					'zpid' => $searchRes->zpid->__toString(),
					'price' => $price ? '$'.$price : '',
					'street' => $searchRes->address->street->__toString(),
					'city' => $searchRes->address->city->__toString(),
					'state' => $searchRes->address->state->__toString(),
				];

				$details = $zillow->getDetails($result['zpid']);
				
				foreach ($details->response->images->image->url as $image) {

					if ($image) $result['photos'][] = $image->__toString();
				}

				$result['rooms'] = sprintf('%s rooms; %s bathrooms;', 
					$details->response->editedFacts->bedrooms->__toString(), 
					$details->response->editedFacts->bathrooms->__toString()
				);

				$result['sqft'] = $details->response->editedFacts->finishedSqFt->__toString() . ' SqFt';

				$this->cache->save($addressInp, $result, 300);
				
			}

			return $this->output
					->set_content_type('application/json')
					->set_output(json_encode($result)
				);
		}

		$this->load->view('zillow/index.php');
	}

}