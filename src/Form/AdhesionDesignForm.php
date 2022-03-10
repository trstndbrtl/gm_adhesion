<?php

namespace Drupal\gm_adhesion\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * PayPal settings form.
 */
class AdhesionDesignForm extends ConfigFormBase {

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
    return 'gm_adhesion_design_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form = array();
    
    $config = $this->config('gm_adhesion.settings');

    $adhesion_charte = isset($config->get('adhesion.adhesion_charte')['value']) ? $config->get('adhesion.adhesion_charte')['value'] : NULL;
    
    $form['adhesion_charte'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Oui, j\'adhère à la charte des valeurs'),
      '#format' => 'mailer',
      '#default_value' => $adhesion_charte,
    );

    $adhesion_etre_informe = isset($config->get('adhesion.adhesion_etre_informe')['value']) ? $config->get('adhesion.adhesion_etre_informe')['value'] : NULL;
    
    $form['adhesion_etre_informe'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Je souhaite être informé(e)'),
      '#format' => 'mailer',
      '#default_value' => $adhesion_etre_informe,
    );

    $adhesion_reception = isset($config->get('adhesion.adhesion_reception')['value']) ? $config->get('adhesion.adhesion_reception')['value'] : NULL;
    
    $form['adhesion_reception'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Je souhaite recevoir des informations'),
      '#format' => 'mailer',
      '#default_value' => $adhesion_reception,
    );

    $adhesion_donnee = isset($config->get('adhesion.adhesion_donnee')['value']) ? $config->get('adhesion.adhesion_donnee')['value'] : NULL;
    
    $form['adhesion_donnee'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Les données recueillies sur ce formulaire sont traitées par...'),
      '#format' => 'mailer',
      '#default_value' => $adhesion_donnee,
    );

    $form_state->setCached(FALSE);

    $form = parent::buildForm($form, $form_state);
    
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('gm_adhesion.settings')
      ->set('adhesion.adhesion_charte', $form_state->getValue('adhesion_charte'))
      ->set('adhesion.adhesion_etre_informe', $form_state->getValue('adhesion_etre_informe'))
      ->set('adhesion.adhesion_reception', $form_state->getValue('adhesion_reception'))
      ->set('adhesion.adhesion_donnee', $form_state->getValue('adhesion_donnee'))
      ->save();

    $msg = 'Les configurations "adhesion_design" ont été enregistrées avec succès';
    $messenger = \Drupal::messenger();
    $messenger->addMessage($msg);
  }

}
