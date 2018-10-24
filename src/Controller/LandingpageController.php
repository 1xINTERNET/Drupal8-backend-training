<?php

namespace Drupal\landingpage\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
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
   * Config factory service.
   *
   * @var ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a LandingpageController object
   * @param Client $httpClient
   * @param LoggerChannelFactory $logger
   * @param ConfigFactoryInterface $configFactory
   */
  public function __construct(Client $httpClient, LoggerChannelFactory $logger, ConfigFactoryInterface $configFactory) {
    $this->httpClient = $httpClient;
    $this->logger = $logger;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('logger.factory'),
      $container->get('config.factory')
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
    $build['#theme'] = 'landingpage';
    // Greetings to our user.
    $build['#greeting'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Hi <b>@username</b> these are some popular star wars movies you might be interested in.', ['@username' => $username])
    ];
    // Retrieve some Star Wars movies through the SWAPI API.
    try {
      $client = $this->httpClient;
      $config = $this->config('landingpage.landingpagesettings');
      $request = $client->request('GET', $config->get('api_url') . '?s=star-wars&type=movie&r=json&apikey=86e4b169');
      $response = $request->getBody();
      $response = Json::decode($response);
      // Loop over all movies.
      if (!empty($response['Search'])) {
        $build['#movies'] = $response['Search'];
      }
    } catch (\Exception $e) {
      $this->logger->get('landingpage')->error($e->getMessage());
    }

    return $build;
  }
}
