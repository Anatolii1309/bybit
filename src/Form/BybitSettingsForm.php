<?php

namespace Drupal\bybit\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Bybit Settings Form.
 */
class BybitSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'bybit_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['bybit.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('bybit.settings');

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#description' => $this->t('The API key of your account on Bybit.'),
    ];

    $form['api_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API secret'),
      '#description' => $this->t('The API secret of your account on Bybit.'),
    ];

    $form['testnet'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Testnet'),
      '#default_value' => $config->get('testnet'),
      '#description' => $this->t('Use test domain https://api-testnet.bybit.com .'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Remove unnecessary values.
    $form_state->cleanValues();
    foreach ($form_state->getValues() as $key => $value) {
      $this->config('bybit.settings')->set($key, $value);
    }
    $this->config('bybit.settings')->save();
    $this->messenger->addMessage($this->t('The configurations are saved.'));
  }

}
