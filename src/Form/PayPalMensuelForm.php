<?php

namespace Drupal\gm_adhesion\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * PayPal settings form.
 */
class PayPalMensuelForm extends ConfigFormBase {

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
    return 'gm_adhesion_paypal_mensuel_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $config = $this->config('gm_adhesion.settings');

    $num_mensuel = $form_state->get('num_mensuel');
    $dp = $config->get('paypal.donation_mensuel');
    $dpc = count($dp);
    
    if (empty($num_mensuel) && empty($dp)) {
      $num_mensuel = $form_state->set('num_mensuel', 1);
    }elseif (empty($num_mensuel) && !empty($dp)) {
      $num_mensuel = $form_state->set('num_mensuel', $dpc);
      $num_mensuel = $dpc;
    }

    $form['#tree'] = TRUE;

    $form['mensuel_fieldset'] = [
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#title' => $this->t('Donation mensuel'),
      '#prefix' => '<div id="names-mensuel-wrapper">',
      '#suffix' => '</div>',
    ];
    
    for ($i = 0; $i < $num_mensuel; $i++) {
      $form['mensuel_fieldset'][$i] = [
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
        '#title' => $this->t('Don ') . ($i + 1),
        '#prefix' => '<div id="names-mensuel-wrapper">',
        '#suffix' => '</div>',
      ];

      $form['mensuel_fieldset'][$i]['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Nom'),
        '#default_value' => isset($dp[$i]['name']) ? $dp[$i]['name'] : '',
      ];

      $form['mensuel_fieldset'][$i]['amount'] = [
        '#type' => 'number',
        '#title' => $this->t('Prix'),
        '#default_value' => isset($dp[$i]['name']) ? $dp[$i]['amount'] : '',
      ];

      $form['mensuel_fieldset'][$i]['code'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Plan id'),
        '#default_value' => isset($dp[$i]['name']) ? $dp[$i]['code'] : '',
      ];

    }

    $form['mensuel_fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#value' => $this->t('Ajouter une donation'),
      '#submit' => array('::addOneMensuel'),
      '#ajax' => [
      'callback' => '::addmoreCallbackMensuel',
      'wrapper' => 'names-mensuel-wrapper',
      ],
    ];

    if ($num_mensuel > 1) {
      $form['mensuel_fieldset']['actions']['remove_name'] = [
      '#type' => 'submit',
      '#value' => $this->t('Supprimer une donation'),
      '#submit' => array('::removeCallbackMensuel'),
       '#ajax' => [
        'callback' => '::addmoreCallbackMensuel',
        'wrapper' => 'names-mensuel-wrapper',
      ]
      ];
    }

    $form_state->setCached(FALSE);

    $form = parent::buildForm($form, $form_state);

    return $form;

  }

  // Mensuel callback
  public function addOneMensuel(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_mensuel');
    $add_button = $name_field + 1;
    $form_state->set('num_mensuel', $add_button);
    $form_state->setRebuild();
  }

  public function addmoreCallbackMensuel(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_mensuel');
    return $form['mensuel_fieldset'];
  }

  public function removeCallbackMensuel(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_mensuel');
    if ($name_field > 1) {
      $remove_button = $name_field - 1;
      $form_state->set('num_mensuel', $remove_button);
    }
    $form_state->setRebuild();
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $donation_mensuel = $form_state->getValue('mensuel_fieldset');
    if ($donation_mensuel && $donation_mensuel[0]) {
      unset($donation_mensuel['actions']);
      for ($i=0; $i < count($donation_mensuel); $i++) { 
        if($donation_mensuel[$i]['name'] == ''){
          unset($donation_mensuel[$i]);
        }
      }
    }
    $this->config('gm_adhesion.settings')
      ->set('paypal.donation_mensuel', $donation_mensuel)
      ->save();

    $msg = 'Les configurations "paypal.donation_mensuel" ont été enregistrées avec succès';
    $messenger = \Drupal::messenger();
    $messenger->addMessage($msg);
  }

}
