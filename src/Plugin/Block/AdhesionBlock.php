<?php

namespace Drupal\gm_adhesion\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'AdhesionBlock' block.
 *
 * @Block(
 *  id = "adhesion_block",
 *  admin_label = @Translation("Adhésion block"),
 * )
 */
class AdhesionBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $gm_block_adhesion = '\Drupal\gm_adhesion\Form\AdhesionForm';

    return [
      '#type' => 'markup',
      'form' => \Drupal::formBuilder()->getForm($gm_block_adhesion),
    ];
  }
}