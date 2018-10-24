<?php

namespace Drupal\landingpage;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class MovieService.
 */
class MovieService {

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Drupal\Core\Logger\LoggerChannelFactoryInterface definition.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new MovieService object.
   * @param ClientInterface $http_client
   * @param LoggerChannelFactoryInterface $logger_factory
   * @param ConfigFactoryInterface $config_factory
   */
  public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory) {
    $this->httpClient = $http_client;
    $this->loggerFactory = $logger_factory;
    $this->configFactory = $config_factory;
  }

  /**
   * @param $name
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getMoviesByName($name) {
    try {
      $movieList = [];
      $client = $this->httpClient;
      $config = $this->configFactory->get('landingpage.landingpagesettings');
      $request = $client->request('GET', $config->get('api_url') . '?s=' . $name . '&type=movie&r=json&apikey=' . $config->get('api_key'));
      $response = $request->getBody();
      $response = Json::decode($response);
      // Loop over all movies.
      if (!empty($response['Search'])) {
        $movieList = $response['Search'];
      }
      return $movieList;
    } catch (\Exception $e) {
      $this->loggerFactor->get('landingpage')->error($e->getMessage());
    }
  }

}
