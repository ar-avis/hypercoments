<?php

namespace Drupal\hypercomments\Plugin\Block;

/**
 * @file
 * Contains \Drupal\hypercomments\Plugin\Block\HyperComments.
 */

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'HyperComments' block.
 *
 * @Block(
 *   id = "block_hypercomments",
 *   admin_label = "HyperComments",
 *   category = @Translation("Custom")
 * )
 */
class HyperComments extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['widget_id'] = [
      '#type' => 'number',
      '#title' => t('Widget id'),
      '#default_value' => \Drupal::config('hypercomments.config')
        ->get('widget_id'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    \Drupal::configFactory()
      ->getEditable('hypercomments.config')
      ->set('widget_id', $form_state->getValue('widget_id'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $widget_id = \Drupal::config('hypercomments.config')
      ->get('widget_id');
    $langcode = \Drupal::languageManager()
      ->getCurrentLanguage()
      ->getId();

    if (!empty($widget_id)) {
      $build = [
        '#markup' => '<div id="hypercomments_widget"></div>',
        '#attached' => [
          'html_head' => [
            [
              [
                '#type' => 'html_tag',
                '#tag' => 'script',
                '#value' => '_hcwp = window._hcwp || [];
                _hcwp.push({widget:"Stream", widget_id: ' . $widget_id . '});
                (function() {
                if("HC_LOAD_INIT" in window)return;
                HC_LOAD_INIT = true;
                var lang = "' . $langcode . '";
                var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
                hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget/hc/'
                . $widget_id . '/"+lang+"/widget.js";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hcc, s.nextSibling);
                })();',
                '#attributes' => ['type' => 'text/javascript'],

              ],
              'hyperComments'
            ]
          ]
        ],
        '#cache' => [
          'keys' => [
            'entity_view',
            'block',
            'block_hypercomments',
            'full'
          ],
          'contexts' => ['languages'],
          'tags' => ['block:block_hypercomments'],
          'max-age' => CacheBackendInterface::CACHE_PERMANENT,
        ]
      ];
    }

    return $build;
  }

}
