<?php

namespace Drupal\landingpage\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class LandingpageController.
 */
class LandingpageController extends ControllerBase {

  /**
   * Returns the landingpage.
   *
   * @return string
   *   Return Hello string.
   */
  public function content() {
    $username = '';
    if (!$this->currentUser()->isAnonymous()) {
      $username = $this->currentUser()->getDisplayName();
    }
    $build = [
      '#type' => 'markup',
      '#markup' => $this->t('Hi <b>@username</b> these are some popular star wars movies you might be interested in.', ['@username' => $username])
    ];

    return $build;
  }

}
