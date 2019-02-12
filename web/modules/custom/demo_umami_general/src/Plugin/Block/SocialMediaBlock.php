<?php

namespace Drupal\demo_umami_general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'SocialMediaBlock' block.
 *
 * @Block(
 *  id = "social_media_block",
 *  admin_label = @Translation("Social media block"),
 * )
 */
class SocialMediaBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $social_config = \Drupal::config('demo_umami_general.socialmediaconfig');
    $build['facebook']['#markup'] =  Link::fromTextAndUrl($this->t('Facebook'), Url::fromUri($social_config->get('facebook')))->toString();
    $build['twitter']['#markup'] = Link::fromTextAndUrl($this->t('Twitter'), Url::fromUri($social_config->get('twitter')))->toString();
    $build['reddit']['#markup'] = Link::fromTextAndUrl($this->t('Reddit'), Url::fromUri($social_config->get('reddit')))->toString();

    return $build;
  }

}
