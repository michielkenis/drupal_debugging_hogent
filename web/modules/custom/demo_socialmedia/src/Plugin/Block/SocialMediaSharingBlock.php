<?php

namespace Drupal\demo_socialmedia\Plugin\Block;

use Drupal\Component\Utility\SortArray;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Template\Attribute;

/**
 * Provides a 'Social media sharing' block.
 *
 * @Block(
 *   id = "demo_socialmedia_sharing",
 *   admin_label = @Translation("Social media sharing block"),
 * )
 */
class SocialMediaSharingBlock extends BlockBase  {

  /**
   * Return a list of available platforms
   */
  private function getPlatforms() {
    $social_config = \Drupal::config('demo_socialmedia.config');
    $social_channels = $social_config->getRawData();

    $social_media = [];
    foreach ($social_channels as $index => $share_url) {
      $social_media[$index] = $share_url;
    }

    return $social_media;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $conf = [];
    foreach ($this->getPlatforms() as $platform => $name) {
      $conf[$platform]['enabled'] = NULL;
      $conf[$platform]['weight'] = 0;
    }
    return $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['table'] = [
      '#type' => 'table',
      '#header' => [t('Platform'), t('Weight'), t('Enabled')],
      '#tableselect' => FALSE,
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'table-order-weight',
        ],
      ],
    ];

    foreach ($this->getPlatforms() as $platform => $name) {
      $rows[$platform]['#attributes']['class'][] = 'draggable';
      $rows[$platform]['#weight'] = $this->configuration[$platform]['weight'];
      $rows[$platform]['label'] = [
        '#plain_text' => ucfirst($platform),
      ];
      $rows[$platform]['weight'] = [
        '#type' => 'weight',
        '#title' => $this->t('Weight for @name', ['@name' => $name]),
        '#title_display' => 'invisible',
        '#default_value' => $this->configuration[$platform]['weight'],
        '#attributes' => ['class' => ['table-order-weight']],
      ];
      $rows[$platform]['element'] = [
        '#type' => 'checkbox',
        '#default_value' => $this->configuration[$platform]['enabled'],
      ];

    }

    // Sort.
    uasort($rows, '\Drupal\Component\Utility\SortArray::sortByWeightProperty');
    $form['table'] = array_merge($form['table'], $rows);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    foreach ($this->getPlatforms() as $platform => $name) {
      $this->configuration[$platform]['enabled'] = $form_state->getValue(['table', $platform, 'element']);
      $this->configuration[$platform]['weight'] = $form_state->getValue(['table', $platform, 'weight']);
    }

  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_path = \Drupal::service('path.current')->getPath();
    $path_alias = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
    $current_url = Url::fromUserInput($path_alias, ['absolute' => TRUE])->toString();

    foreach ($this->getPlatforms() as $platform => $platform_share) {
      if ($this->configuration[$platform]['is_enabled']) {
        $links[$platform] = [
          'title' => ucfirst($platform),
          'url' => Url::fromUri($platform_share . $current_url)->toString(),
          'attributes' => new Attribute(['class' => 'social-share-link--' . $platform . ' icon-social-' . $platform, 'target' => "_blank"]),
        ];
      }
    }

    uasort($links, '\Drupal\Component\Utility\SortArray::sortByWeightElement');
    $build = [
      '#theme' => 'demo_social_sharing',
      '#links' => $links,
      '#attached' => [
        'library' => [
          'demo_socialmedia/demo_socialmedia',
        ],
      ],
    ];

    return $build;
  }

}
