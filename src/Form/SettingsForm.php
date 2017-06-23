<?php

namespace Drupal\hypercomments\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a settings form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hypercomments_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['hypercomments.config'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['widget_id'] = [
      '#type' => 'number',
      '#title' => t('Widget id'),
      '#default_value' => $this->config('hypercomments.config')
        ->get('widget_id'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('hypercomments.config')
      ->set('widget_id', $form_state->getValue('widget_id'))
      ->save();
  }

}
