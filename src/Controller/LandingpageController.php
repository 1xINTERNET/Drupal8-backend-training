<?php

namespace Drupal\landingpage\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class LandingpageController.
 */
class LandingpageController extends ControllerBase {

  /**
   * Content.
   *
   * @return string
   *   Return Hello string.
   */
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: content')
    ];
  }

}
