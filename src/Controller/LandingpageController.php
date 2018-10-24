<?php

namespace Drupal\landingpage\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\landingpage\MovieService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LandingpageController.
 */
class LandingpageController extends ControllerBase {

  /**
   * MovieService.
   *
   * @var MovieService
   */
  protected $movieService;

  /**
   * Constructs a LandingpageController object
   * @param MovieService $movieService
   */
  public function __construct(MovieService $movieService) {
    $this->movieService = $movieService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('landingpage.movie_service')
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
    $build['#movie_list'] = [
      '#theme' => 'movie_list',
      '#movies' => $this->movieService->getMoviesByName('star wars')
    ];

    return $build;
  }
}
