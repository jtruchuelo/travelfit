<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use DateInterval;
use GuzzleHttp\Client;

class SygicAPI
{
	protected $client;

	public function __construct(Client $client)	{
		$this->client = $client;
  }

  // Obtener datos básicos del destino itinerario
  public function getDestinationData($destination) {
      $params = [
          'query' => [
              'query' => $destination,
              'levels' => 'city|town|village',
              'tags' => 'city|town|village',
              'limit' => '1'
          ]
      ];
      return $this->endpointRequest('places/list', $params);
  }

  // Obtener listado POIs de un destino
  public function getDestinationPois($destination, $categories, $limit){
      $params = [
          'query' => [
              'parents' => $destination,
              'levels' => 'poi',
              'limit' => $limit,
              'categories' => implode('|', $categories),
              'categories_not' => 'traveling'
          ]
      ];
      return $this->endpointRequest('places/list', $params);
  }

  // Obtiene detalle de uno o varios elementos
  public function getDetails($ids, $multiple = false) {
    if ($multiple) {
        $params = [
            'query' => [
                'ids' => implode("|", $ids)
            ]
        ];
        return $this->endpointRequest('places', $params);
    } else {
        $id = str_replace(":", "%3A", $ids);
        $photo = $this->endpointRequest('places/'.$id.'/media', null);
        for ($i = 0; $i<count($photo->data->media); $i++) {
            if ($photo->data->media[$i]->type == "photo") {
                return $photo->data->media[$i]->url;
                break;
            }
        }
    }
  }

  private function endpointRequest($url, $params)
  {
    try {
            if ($params != null) {
                $response = $this->client->request('GET', $url, $params);
            } else {
                $response = $this->client->request('GET', $url);
            }

            /* if ($params && $params['query']) {
                $response = $this->client->request('GET', $url, $params);
                /* $response = Cache::remember($url.$params['query'], new DateInterval("P2W"), function() use ($url, $params){
                    $response = $this->client->request('GET', $url, $params);
                    return $response;
                });
            } else {
                $response = $this->client->request('GET', $url);
                /* $response = Cache::remember($url, new DateInterval("P2W"), function() use ($url){
                    $response = $this->client->request('GET', $url);
                    return $response;
                });
            } */

    } catch (\Exception $e) {
            return [];
    }

    return $this->response_handler($response->getBody()->getContents());
  }

  private function response_handler($response)
  {
    if ($response) {
      return json_decode($response);
    }

    return [];
  }
}
