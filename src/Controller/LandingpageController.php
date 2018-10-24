<?php

namespace Drupal\landingpage\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class LandingpageController.
 */
class LandingpageController extends ControllerBase {

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
      $client = \Drupal::httpClient();
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
      \Drupal::logger('landingpage')->error($e->getMessage());
    }

    return $build;
  }
}
