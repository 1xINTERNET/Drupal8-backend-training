<?php

namespace Drupal\landingpage\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class LandingPageSettingsForm.
 */
class LandingPageSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'landingpage.landingpagesettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'landing_page_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('landingpage.landingpagesettings');
    $form['api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API URL'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('api_url'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('landingpage.landingpagesettings')
      ->set('api_url', $form_state->getValue('api_url'))
      ->save();
  }

}
