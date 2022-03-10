<?php

namespace Drupal\gm_adhesion\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;

/**
 * PayPal settings form.
 */
class PayPalDesignForm extends ConfigFormBase {

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
    return 'gm_adhesion_design_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form = array();
    
    $config = $this->config('gm_adhesion.settings');

    $form['don_souhaite'] = [
      '#title' => $this->t('Vous êtes une majorité à donner'),
      '#type' => 'number',
      '#default_value' => $config->get('paypal.don_souhaite'),
      '#description' => $this->t(
        'Montant indiquant aux internets.'
      ),
    ];

    $form['taux_imposition'] = [
      '#title' => $this->t('% du taux d\'impôsition'),
      '#type' => 'number',
      '#default_value' => $config->get('paypal.taux_imposition'),
      '#description' => $this->t('Soit ($don * 34 / 100) après réduction d’impôt'),
    ];

    $personne_morale = isset($config->get('paypal.personne_morale')['value']) ? $config->get('paypal.personne_morale')['value'] : NULL;
    
    $form['personne_morale'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Je certifie sur l\'honneur être une personne physique'),
      '#format' => 'mailer',
      '#default_value' => $personne_morale,
    );

    $nationalite = isset($config->get('paypal.personne_nationalite')['value']) ? $config->get('paypal.personne_nationalite')['value'] : NULL;
    
    $form['personne_nationalite'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Je certifie être de nationalité française'),
      '#format' => 'mailer',
      '#default_value' => $nationalite,
    );

    $mentions = isset($config->get('paypal.personne_mentions')['value']) ? $config->get('paypal.personne_mentions')['value'] : NULL;
    
    $form['personne_mentions'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('J\'ai lu et j\'accepte les mentions'),
      '#format' => 'mailer',
      '#default_value' => $mentions,
    );

    $destinataire_don = isset($config->get('paypal.destinataire_don')['value']) ? $config->get('paypal.destinataire_don')['value'] : NULL;
    
    $form['destinataire_don'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Votre don sera versé à l\'Association'),
      '#format' => 'mailer',
      '#default_value' => $destinataire_don,
    );

    $donnee_formulaire = isset($config->get('paypal.donnee_formulaire')['value']) ? $config->get('paypal.donnee_formulaire')['value'] : NULL;
    
    $form['donnee_formulaire'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Les données recueillies sur ce formulaire sont traitées par...'),
      '#format' => 'mailer',
      '#default_value' => $donnee_formulaire,
    );

    $page_donation_deffault = NULL;
    $page_donation = ($config->get('paypal.page_donation') !== NULL) ? $config->get('paypal.page_donation') : NULL;
    if ($page_donation) {
      $page_donation_deffault = Node::load($page_donation);
    }

    $form['page_donation'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Donation page'),
      '#target_type'   => 'node',
      '#default_value' => $page_donation_deffault,
      '#allowed_bundles' => ['page'],
    ];

    
    $bg_validate_page_default = NULL;
    $bg_validate_page = ($config->get('paypal.bg_validate_page') !== NULL) ? $config->get('paypal.bg_validate_page') : NULL;
    if ($bg_validate_page) {
      $bg_validate_page_default = Media::load($bg_validate_page);
    }

    $form['bg_validate_page'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Background validate page'),
      '#target_type'   => 'media',
      '#default_value' => $bg_validate_page_default,
      '#allowed_bundles' => ['image'],
    ];

    $bg_merci_page_default = NULL;
    $bg_merci_page = ($config->get('paypal.bg_merci_page') !== NULL) ? $config->get('paypal.bg_merci_page') : NULL;
    if ($bg_merci_page) {
      $bg_merci_page_default = Media::load($bg_merci_page);
    }

    $form['bg_merci_page'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Background Merci page'),
      '#target_type'   => 'media',
      '#default_value' => $bg_merci_page_default,
      '#allowed_bundles' => ['image'],
    ];

    $msg_merci_page = isset($config->get('paypal.msg_merci_page')['value']) ? $config->get('paypal.msg_merci_page')['value'] : NULL;
    
    $form['msg_merci_page'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Message affiché sur la page de remerciment.'),
      '#format' => 'mailer',
      '#default_value' => $msg_merci_page,
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
      ->set('paypal.don_souhaite', $form_state->getValue('don_souhaite'))
      ->set('paypal.taux_imposition', $form_state->getValue('taux_imposition'))
      ->set('paypal.personne_morale', $form_state->getValue('personne_morale'))
      ->set('paypal.personne_nationalite', $form_state->getValue('personne_nationalite'))
      ->set('paypal.personne_mentions', $form_state->getValue('personne_mentions'))
      ->set('paypal.destinataire_don', $form_state->getValue('destinataire_don'))
      ->set('paypal.donnee_formulaire', $form_state->getValue('donnee_formulaire'))
      ->set('paypal.bg_validate_page', $form_state->getValue('bg_validate_page'))
      ->set('paypal.page_donation', $form_state->getValue('page_donation'))
      ->set('paypal.msg_merci_page', $form_state->getValue('msg_merci_page'))
      ->set('paypal.bg_merci_page', $form_state->getValue('bg_merci_page'))
      ->save();

    $msg = 'Les configurations "paypal.don" ont été enregistrées avec succès';
    $messenger = \Drupal::messenger();
    $messenger->addMessage($msg);
  }

}
