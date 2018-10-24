<?php

namespace Drupal\landingpage\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\landingpage\MovieService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'MovieListBlock' block.
 *
 * @Block(
 *  id = "movie_list_block",
 *  admin_label = @Translation("Movie list block"),
 * )
 */
class MovieListBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var MovieService
   */
  protected $movieService;

  /**
   * MovieListBlock constructor.
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param MovieService $movieService
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MovieService $movieService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->movieService = $movieService;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('landingpage.movie_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
          ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['movie_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Movie name'),
      '#default_value' => $this->configuration['movie_name'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['movie_name'] = $form_state->getValue('movie_name');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['movies'] = [
      '#theme' => 'movie_list',
      '#movies' => $this->movieService->getMoviesByName($this->configuration['movie_name'])
    ];

    return $build;
  }
}
