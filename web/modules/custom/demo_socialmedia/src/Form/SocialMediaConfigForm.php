<?php

namespace Drupal\demo_socialmedia\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SocialMediaConfigForm.
 */
class SocialMediaConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'demo_socialmedia.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_media_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('demo_socialmedia.config');

    $form['facebook'] = [
      '#type' => 'url',
      '#title' => $this->t('Facebook'),
      '#default_value' => $config->get('facebook'),
      '#description' => 'Example: http://facebook.com/sharer.php?u=',
    ];

    $form['twitter'] = [
      '#type' => 'url',
      '#title' => $this->t('Twitter'),
      '#default_value' => $config->get('facebook'),
      '#description' => 'Example: http://twitter.com/intent/tweet?url=',
    ];

    $form['reddit'] = [
      '#type' => 'url',
      '#title' => $this->t('Reddit'),
      '#default_value' => $config->get('reddit'),
      '#description' => 'Example: https://www.reddit.com/submit?url=',
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

    $this->config('demo_socialmedia.config')
      ->set('facebook', $form_state->getValue('facebook_share'))
      ->save();
  }

}
