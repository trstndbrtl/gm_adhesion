<?php

namespace Drupal\gm_adhesion\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * PayPal settings form.
 */
class MailerMessagesForm extends ConfigFormBase {

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
    return 'gm_mailer_messages_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form = array();
    
    $config = $this->config('gm_adhesion.settings');

    $logo_header = ($config->get('mailer_sys.logo_header')  !== NULL) ? $config->get('mailer_sys.logo_header') : NULL;

    $form['logo_header'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Url du logo'),
      '#default_value' => $logo_header,
    ];

    $email_admin = ($config->get('mailer_sys.email_admin') !== NULL) ? $config->get('mailer_sys.email_admin') : NULL;

    $form['email_admin'] = [
      '#type' => 'email',
      '#title' => $this->t('L\'email admin qui recevra les notifications'),
      '#default_value' => $email_admin,
    ];

    // NOUVELL ADHESION
    $msg_nouvelle_adhesion_title = ($config->get('mailer_sys.msg_nouvelle_adhesion_title')  !== NULL) ? $config->get('mailer_sys.msg_nouvelle_adhesion_title') : NULL;

    $form['msg_nouvelle_adhesion_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre nouvelle adhesion'),
      '#default_value' => $msg_nouvelle_adhesion_title,
    ];

    $msg_nouvelle_adhesion = isset($config->get('mailer_sys.msg_nouvelle_adhesion')['value']) ? $config->get('mailer_sys.msg_nouvelle_adhesion')['value'] : NULL;
    
    $form['msg_nouvelle_adhesion'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Message pour les nouvelles adhésions'),
      // '#format' => 'full_html',
      '#format' => 'mailer',
      '#default_value' => $msg_nouvelle_adhesion,
    );

    // MÊME MAIL
    $msg_adhesion_meme_email_title = ($config->get('mailer_sys.msg_adhesion_meme_email_title')  !== NULL) ? $config->get('mailer_sys.msg_adhesion_meme_email_title') : NULL;

    $form['msg_adhesion_meme_email_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre même mail'),
      '#default_value' => $msg_adhesion_meme_email_title,
    ];

    $msg_adhesion_meme_email = isset($config->get('mailer_sys.msg_adhesion_meme_email')['value']) ? $config->get('mailer_sys.msg_adhesion_meme_email')['value'] : NULL;
    
    $form['msg_adhesion_meme_email'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Message dans le cas d\'un utilisateur déjà adhérent'),
      '#format' => 'mailer',
      '#default_value' => $msg_adhesion_meme_email,
    );

    // STOP ADHESION
    $msg_stop_adhesion_title = ($config->get('mailer_sys.msg_stop_adhesion_title')  !== NULL) ? $config->get('mailer_sys.msg_stop_adhesion_title') : NULL;

    $form['msg_stop_adhesion_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre stop adhésion'),
      '#default_value' => $msg_stop_adhesion_title,
    ];

    $msg_stop_adhesion = isset($config->get('mailer_sys.msg_stop_adhesion')['value']) ? $config->get('mailer_sys.msg_stop_adhesion')['value'] : NULL;
    
    $form['msg_stop_adhesion'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Message envoyer après qu\'un utilisateur stop son adhésion.'),
      '#format' => 'mailer',
      '#default_value' => $msg_stop_adhesion,
    );

    // NOUVEAU DON
    $msg_nouveau_don_title = ($config->get('mailer_sys.msg_nouveau_don_title')  !== NULL) ? $config->get('mailer_sys.msg_nouveau_don_title') : NULL;

    $form['msg_nouveau_don_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre nouveau don'),
      '#default_value' => $msg_nouveau_don_title,
    ];

    $msg_nouveau_don = isset($config->get('mailer_sys.msg_nouveau_don')['value']) ? $config->get('mailer_sys.msg_nouveau_don')['value'] : NULL;
    
    $form['msg_nouveau_don'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Message envoyé après un nouveau don.'),
      '#format' => 'mailer',
      '#default_value' => $msg_nouveau_don,
    );

    // INSCRIPTION NEWLETTER
    $msg_inscription_news_title = ($config->get('mailer_sys.msg_inscription_news_title')  !== NULL) ? $config->get('mailer_sys.msg_inscription_news_title') : NULL;

    $form['msg_inscription_news_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre inscription newsletter'),
      '#default_value' => $msg_inscription_news_title,
    ];

    $msg_inscription_news = isset($config->get('mailer_sys.msg_inscription_news')['value']) ? $config->get('mailer_sys.msg_inscription_news')['value'] : NULL;
    
    $form['msg_inscription_news'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Message envoyé après qu\'un utilisateur s\inscrit à la newsletter.'),
      '#format' => 'mailer',
      '#default_value' => $msg_inscription_news,
    );

    // STOP NEWSLETTER
    $msg_stop_news_title = ($config->get('mailer_sys.msg_stop_news_title')  !== NULL) ? $config->get('mailer_sys.msg_stop_news_title') : NULL;

    $form['msg_stop_news_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre stop newsletter'),
      '#default_value' => $msg_stop_news_title,
    ];

    $msg_stop_news = isset($config->get('mailer_sys.msg_stop_news')['value']) ? $config->get('mailer_sys.msg_stop_news')['value'] : NULL;
    
    $form['msg_stop_news'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Message envoyé après qu\'un utilisateur stop sa souscription à la newsletter'),
      '#format' => 'mailer',
      '#default_value' => $msg_stop_news,
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
      ->set('mailer_sys.msg_nouvelle_adhesion_title', strip_tags($form_state->getValue('msg_nouvelle_adhesion_title')))
      ->set('mailer_sys.msg_nouvelle_adhesion', $form_state->getValue('msg_nouvelle_adhesion'))
      ->set('mailer_sys.msg_adhesion_meme_email_title', strip_tags($form_state->getValue('msg_adhesion_meme_email_title')))
      ->set('mailer_sys.msg_adhesion_meme_email', $form_state->getValue('msg_adhesion_meme_email'))
      ->set('mailer_sys.msg_stop_adhesion_title', strip_tags($form_state->getValue('msg_stop_adhesion_title')))
      ->set('mailer_sys.msg_stop_adhesion', $form_state->getValue('msg_stop_adhesion'))
      ->set('mailer_sys.msg_nouveau_don_title', strip_tags($form_state->getValue('msg_nouveau_don_title')))
      ->set('mailer_sys.msg_nouveau_don', $form_state->getValue('msg_nouveau_don'))
      ->set('mailer_sys.msg_inscription_news_title', strip_tags($form_state->getValue('msg_inscription_news_title')))
      ->set('mailer_sys.msg_inscription_news', $form_state->getValue('msg_inscription_news'))
      ->set('mailer_sys.msg_stop_news_title', strip_tags($form_state->getValue('msg_stop_news_title')))
      ->set('mailer_sys.msg_stop_news', $form_state->getValue('msg_stop_news'))
      ->set('mailer_sys.logo_header', strip_tags($form_state->getValue('logo_header')))
      ->set('mailer_sys.email_admin', $form_state->getValue('email_admin'))
      ->save();

    $msg = 'Les configurations "mailer_sys<" ont été enregistrées avec succès';
    $messenger = \Drupal::messenger();
    $messenger->addMessage($msg);
  }

}
