<?php

namespace Drupal\gm_adhesion\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * PayPal settings form.
 */
class PayPalPonctuelForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['gm_adhesion.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gm_adhesion_paypal_ponctuel_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $config = $this->config('gm_adhesion.settings');

    $num_ponctuel = $form_state->get('num_ponctuel');
    $dp = $config->get('paypal.donation_ponctuel');
    $dpc = count($dp);
    
    if (empty($num_ponctuel) && empty($dp)) {
      $num_ponctuel = $form_state->set('num_ponctuel', 1);
    }elseif (empty($num_ponctuel) && !empty($dp)) {
      $num_ponctuel = $form_state->set('num_ponctuel', $dpc);
      $num_ponctuel = $dpc;
    }

    $form['#tree'] = TRUE;

    $form['ponctuel_fieldset'] = [
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#title' => $this->t('Donation ponctuel'),
      '#prefix' => '<div id="names-ponctuel-wrapper">',
      '#suffix' => '</div>',
      '#description' => $this->t('Pour utiliser le bouton autre : Autre/0'),
    ];
    
    for ($i = 0; $i < $num_ponctuel; $i++) {
      $form['ponctuel_fieldset'][$i] = [
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
        '#title' => $this->t('Don ') . ($i + 1),
        '#prefix' => '<div id="names-ponctuel-wrapper">',
        '#suffix' => '</div>',
      ];

      $form['ponctuel_fieldset'][$i]['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Nom'),
        '#default_value' => isset($dp[$i]['name']) ? $dp[$i]['name'] : '',
      ];

      $form['ponctuel_fieldset'][$i]['amount'] = [
        '#type' => 'number',
        '#title' => $this->t('Prix'),
        '#default_value' => isset($dp[$i]['name']) ? $dp[$i]['amount'] : '',
      ];

      $form['ponctuel_fieldset'][$i]['code'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Code'),
        '#default_value' => isset($dp[$i]['name']) ? $dp[$i]['code'] : '',
      ];

    }

    $form['ponctuel_fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#value' => $this->t('Ajouter une donation'),
      '#submit' => array('::addOnePonctuel'),
      '#ajax' => [
      'callback' => '::addmoreCallbackPonctuel',
      'wrapper' => 'names-ponctuel-wrapper',
      ],
    ];

    if ($num_ponctuel > 1) {
      $form['ponctuel_fieldset']['actions']['remove_name'] = [
      '#type' => 'submit',
      '#value' => $this->t('Supprimer une donation'),
      '#submit' => array('::removeCallbackPonctuel'),
       '#ajax' => [
        'callback' => '::addmoreCallbackPonctuel',
        'wrapper' => 'names-ponctuel-wrapper',
      ]
      ];
    }

    $form_state->setCached(FALSE);

    $form = parent::buildForm($form, $form_state);

    return $form;

  }

  // Ponctuel callback
  public function addOnePonctuel(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_ponctuel');
    $add_button = $name_field + 1;
    $form_state->set('num_ponctuel', $add_button);
    $form_state->setRebuild();
  }

  public function addmoreCallbackPonctuel(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_ponctuel');
    return $form['ponctuel_fieldset'];
  }

  public function removeCallbackPonctuel(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_ponctuel');
    if ($name_field > 1) {
      $remove_button = $name_field - 1;
      $form_state->set('num_ponctuel', $remove_button);
    }
    $form_state->setRebuild();
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $donation_ponctuel = $form_state->getValue('ponctuel_fieldset');
    if ($donation_ponctuel && $donation_ponctuel[0]) {
      unset($donation_ponctuel['actions']);
      for ($i=0; $i < count($donation_ponctuel); $i++) { 
        if($donation_ponctuel[$i]['name'] == ''){
          unset($donation_ponctuel[$i]);
        }
      }
    }
    $this->config('gm_adhesion.settings')
      ->set('paypal.donation_ponctuel', $donation_ponctuel)
      ->save();

    $msg = 'Les configurations "paypal.donation_ponctuel" ont été enregistrées avec succès';
    $messenger = \Drupal::messenger();
    $messenger->addMessage($msg);
  }

}
