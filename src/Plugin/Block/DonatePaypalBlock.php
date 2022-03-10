<?php

namespace Drupal\gm_adhesion\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'DonateBlock' block.
 *
 * @Block(
 *  id = "donate_paypal_block",
 *  admin_label = @Translation("Donate Paypal block"),
 * )
 */
class DonatePaypalBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form_to_load = '\Drupal\gm_adhesion\Form\DonateForm';

    return [
      '#type' => 'markup',
      'form' => \Drupal::formBuilder()->getForm($form_to_load),
      '#attached' => [
        'library' => [
          'gm_adhesion/gm-design-all',
        ],
      ],
    ];
  }
}