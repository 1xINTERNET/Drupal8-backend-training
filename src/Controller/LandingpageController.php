<?php

namespace Drupal\landingpage\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\LoggerChannelFactory;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LandingpageController.
 */
class LandingpageController extends ControllerBase {

  /**
   * Guzzle Http Client.
   *
   * @var GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The logger service.
   *
   * @var LoggerChannelFactory
   */
  protected $logger;

  /**
   * Constructs a LandingpageController object
   * @param Client $httpClient
   * @param LoggerChannelFactory $logger
   */
  public function __construct(Client $httpClient, LoggerChannelFactory $logger) {
    $this->httpClient = $httpClient;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('logger.factory')
    );
  }

  /**
   * Returns the landingpage with popular star wars movies.
   */
  public function content() {
    $username = '';
    if (!$this->currentUser()->isAnonymous()) {
      $username = $this->currentUser()->getDisplayName();
    }
    // Greetings to our user.
    $build = [
      '#type' => 'markup',
      '#markup' => $this->t('Hi <b>@username</b> these are some popular star wars movies you might be interested in.', ['@username' => $username])
    ];
    // Retrieve some Star Wars movies through the SWAPI API.
    try {
      $client = $this->httpClient;
      $request = $client->request('GET', 'http://www.omdbapi.com/?s=star-wars&type=movie&r=json&apikey=86e4b169');
      $response = $request->getBody();
      $response = Json::decode($response);
      // Loop over all movies.
      if (!empty($response['Search'])) {
        foreach ($response['Search'] as $movie) {
          $build['#markup'] .= '</br><b>' . $movie['Title'] . '<b/>';
        }
      }
    } catch (\Exception $e) {
      $this->logger->get('landingpage')->error($e->getMessage());
    }

    return $build;
  }
}
