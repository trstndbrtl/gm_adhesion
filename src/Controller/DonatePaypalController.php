<?php

namespace Drupal\gm_adhesion\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Component\Utility\Xss;
use Drupal\media\Entity\Media;
use Drupal\image\Entity\ImageStyle;
use Drupal\media_entity\MediaInterface;

/**
 * Controller class for requests from the smart buttons.
 */
class DonatePaypalController extends ControllerBase {

  /**
   * Drupal event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('event_dispatcher'));
  }

  /**
   * DonatePaypalController constructor.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   Drupal event dispatcher.
   */
  public function __construct(EventDispatcherInterface $eventDispatcher) {
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * Hello.
   *
   */
  public function validerMaDonation() {

    // 1. Get the private tempstore factory, inject this in your form, controller or service.
    $tempstore = \Drupal::service('tempstore.private');
    // Get the store collection. 
    $store = $tempstore->get('gm_adhesion');

    $donation_type_p = $store->get('donation_type_p');
    $amount_list_ponctuel = $store->get('amount_list_ponctuel');
    $amount_list_mensuel = $store->get('amount_list_mensuel');
    $autre_montant = $store->get('autre_montant');
    $donation_amount = $store->get('donation_amount');

    // Get Paypal configuration
    $config = \Drupal::config('gm_adhesion.settings');
    // $paypal_settings = $config->get('paypal');

    $donnee_formulaire = isset($config->get('paypal.donnee_formulaire')['value']) ? $config->get('paypal.donnee_formulaire')['value'] : NULL;
    $destinataire_don = isset($config->get('paypal.destinataire_don')['value']) ? $config->get('paypal.destinataire_don')['value'] : NULL;
    $client_id = ($config->get('paypal.client_id') !== NULL) ? $config->get('paypal.client_id') : NULL;

    $bg_page = NULL;
    $bg_validate_danation = ($config->get('paypal.bg_validate_page') !== NULL) ? $config->get('paypal.bg_validate_page') : NULL;
    if ($bg_validate_danation) {
      $bg = Media::load($bg_validate_danation);
      if ($bg) {
        /** @var \Drupal\media\MediaInterface $bg */
        $media_uri = $bg->field_media_image->entity->getFileUri();
        $bg_page = ImageStyle::load('full')->buildUrl($media_uri);
      }
    }

    $donation_nid = ($config->get('paypal.page_donation') !== NULL) ? $config->get('paypal.page_donation') : NULL;
    
    return [
      '#theme' => 'donate_paypal',
      '#donation_type_p' => $donation_type_p,
      '#amount_list_ponctuel' => $amount_list_ponctuel,
      '#amount_list_mensuel' => $amount_list_mensuel,
      '#autre_montant' => $autre_montant,
      '#donation_amount' => $donation_amount,
      '#bg_page' => $bg_page,
      '#donation_nid' => $donation_nid,
      '#don_souhaite' => NULL,
      '#taux_imposition' => NULL,
      '#personne_nationalite' => NULL,
      '#personne_mentions' => NULL,
      '#personne_morale' => NULL,
      '#donnee_formulaire' => $donnee_formulaire,
      '#destinataire_don' => $destinataire_don,
      '#client' => $client_id,
    ];
  }

  /**
   * Hello.
   *
   */
  public function merciBeaucoup() {

    // 1. Get the private tempstore factory, inject this in your form, controller or service.
    $tempstore = \Drupal::service('tempstore.private');
    $store = $tempstore->get('gm_adhesion');

    $donation_type_p = $store->get('donation_type_p');
    $donation_amount = $store->get('donation_amount');
    $prenom_dntr = ($store->get('portrait') !== NULL && $store->get('portrait')['prenom'] !== NULL) ? $store->get('portrait')['prenom'] : NULL;
    $nom_dntr = ($store->get('portrait') !== NULL && $store->get('portrait')['nom'] !== NULL) ? $store->get('portrait')['nom'] : NULL;
    $nom_donateur = ($prenom_dntr && $nom_dntr) ? $prenom_dntr .' '. $nom_dntr : NULL;
    // Get Paypal configuration
    $config = \Drupal::config('gm_adhesion.settings');
    // $paypal_settings = $config->get('paypal');
    $donnee_formulaire = isset($config->get('paypal.donnee_formulaire')['value']) ? $config->get('paypal.donnee_formulaire')['value'] : NULL;
    $destinataire_don = isset($config->get('paypal.destinataire_don')['value']) ? $config->get('paypal.destinataire_don')['value'] : NULL;
    $client_id = ($config->get('paypal.client_id') !== NULL) ? $config->get('paypal.client_id') : NULL;

    $bg_page = NULL;
    $bg_merci_page = ($config->get('paypal.bg_merci_page') !== NULL) ? $config->get('paypal.bg_merci_page') : NULL;
    if ($bg_merci_page) {
      $bg = Media::load($bg_merci_page);
      if ($bg) {
        /** @var \Drupal\media\MediaInterface $bg */
        $media_uri = $bg->field_media_image->entity->getFileUri();
        $bg_page = ImageStyle::load('full')->buildUrl($media_uri);
      }
    }

    $donation_nid = ($config->get('paypal.page_donation') !== NULL) ? $config->get('paypal.page_donation') : NULL;
    $msg_merci_page = ($config->get('paypal.msg_merci_page') !== NULL && $config->get('paypal.msg_merci_page')['value'] != '') ? $config->get('paypal.msg_merci_page')['value'] : NULL;

    return [
      '#theme' => 'donate_merci',
      '#donation_type_p' => $donation_type_p,
      '#donation_amount' => $donation_amount,
      '#notification' => $msg_merci_page,
      '#nom_donateur' => $nom_donateur,
      '#bg_page' => $bg_page,
      '#donation_nid' => $donation_nid,
      '#name' => NULL,
    ];
  }

  /**
   * Callback requested after approving the order.
   *
   * Dispatches the 'approve' event to Drupal.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Response for buttons.
   */
  public function approve(Request $request) {
    $content = $request->getContent();
    if (!empty($content)) {
      $data = json_decode($content, TRUE);
      $element = [];
      // Infor user
      $tempstore = \Drupal::service('tempstore.private');
      $store = $tempstore->get('gm_adhesion');
      $street = ($store->get('portrait') !== NULL && $store->get('portrait')['street'] !== NULL) ? $store->get('portrait')['street'] : NULL;
      $city = ($store->get('portrait') !== NULL && $store->get('portrait')['city'] !== NULL) ? $store->get('portrait')['city'] : NULL;
      $postal_code = ($store->get('portrait') !== NULL && $store->get('portrait')['postal_code'] !== NULL) ? $store->get('portrait')['postal_code'] : NULL;
      $genre = ($store->get('portrait') !== NULL && $store->get('portrait')['genre'] !== NULL) ? $store->get('portrait')['genre'] : NULL;
      $nationalite = ($store->get('portrait') !== NULL && $store->get('portrait')['nationalite'] !== NULL) ? $store->get('portrait')['nationalite'] : NULL;
      $prenom = ($store->get('portrait') !== NULL && $store->get('portrait')['prenom'] !== NULL) ? $store->get('portrait')['prenom'] : NULL;
      $nom = ($store->get('portrait') !== NULL && $store->get('portrait')['nom'] !== NULL) ? $store->get('portrait')['nom'] : NULL;
      $email = ($store->get('portrait') !== NULL && $store->get('portrait')['email'] !== NULL) ? $store->get('portrait')['email'] : NULL;
      $payer = ($data['capture_details']['payer'] !== NULL) ? json_encode($data['capture_details']['payer']) : '{"hello":"world '.\Drupal::time()->getRequestTime().'"}';
      // Info paypal
      $subscription = NULL;
      $plan_id = NULL;
      $facilitator_token = ($data['capture_data']['facilitatorAccessToken'] !== NULL) ? $data['capture_data']['facilitatorAccessToken'] : NULL;
      $order_id = ($data['capture_data']['orderID'] !== NULL) ? $data['capture_data']['orderID'] : NULL;
      $donation_type_p = ($store->get('donation_type_p') == '1') ? $data['capture_data']['billingToken'] : NULL;
      if ($store->get('donation_type_p') == '2') {
        $subscription = ($data['capture_data']['subscriptionID'] !== NULL) ? $data['capture_data']['subscriptionID'] : NULL;
        $plan_id = ($data['capture_data']['subscriptionID'] !== NULL) ? $data['capture_data']['subscriptionID'] : NULL;
      }else{
        $subscription  = ($data['capture_data']['payerID'] !== NULL) ? $data['capture_data']['payerID'] : NULL;
      }
      if ($data) {
        $connection = \Drupal::service('database');
        $connection
          ->merge('users_paypal_donation')
          ->key('order_id', $order_id)
          ->insertFields(array(
            'facilitator_token' => $facilitator_token,
            'order_id' => $order_id,
            'billing_token' => $billing_token,
            'subscription_if' => $subscription,
            'plan_id' => $plan_id,
            'email' => $email,
            'gender' => $genre,
            'name' => $nom,
            'forname' => $prenom,
            'nationality' => $nationalite,
            'adresse' => $street,
            'postal_code' => $postal_code,
            'city' => $city,
            'status' => 1,
            'honneur_certification' => 1,
            'nationalite_certification' => 1,
            'information_desire' => 1,
            'object_paypal' => $payer,
            'timestamp' => \Drupal::time()->getRequestTime()
          ))
          ->updateFields(array(
            'facilitator_token' => $facilitator_token,
            'order_id' => $order_id,
            'billing_token' => $billing_token,
            'subscription_if' => $subscription,
            'plan_id' => $plan_id,
            'email' => $email,
            'gender' => $genre,
            'name' => $nom,
            'forname' => $prenom,
            'nationality' => $nationalite,
            'adresse' => $street,
            'postal_code' => $postal_code,
            'city' => $city,
            'status' => 1,
            'honneur_certification' => 1,
            'nationalite_certification' => 1,
            'information_desire' => 1,
            'object_paypal' => $payer,
            'timestamp' => \Drupal::time()->getRequestTime()
          ))->execute();
      }
      return new JsonResponse('ok');
    }
    else {
      return new JsonResponse(NULL, Response::HTTP_NOT_ACCEPTABLE);
    }
  }

  /**
   *
   * Dispatches the donor information event to Drupal.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Response for buttons.
   */
  public function subscription(Request $request) {
    $content = $request->getContent();
    if (!empty($content)) {
      $data = json_decode($content, TRUE);
      $element = [];

      $payer = ($data['capture_details']['payer'] !== NULL) ? json_encode($data['capture_details']['payer']) : NULL;
      $order_id = ($data['order_id']!== NULL) ? $data['order_id'] : NULL;

      if ($payer &&  $order_id) {
        $connection = \Drupal::service('database');
        $connection
          ->merge('users_paypal_donation')
          ->key('order_id', $order_id)
          ->insertFields(array(
            'object_paypal' => $payer,
            'order_id' => $order_id,
          ))
          ->updateFields(array(
            'object_paypal' => $payer,
            'order_id' => $order_id,
          ))->execute();
      }
      return new JsonResponse('ok');
    }
    else {
      return new JsonResponse(NULL, Response::HTTP_NOT_ACCEPTABLE);
    }
  }

  /**
   * @return JsonResponse
   */
  public function findCity(Request $request) {
    $results = [];
    $input = $request->query->get('q');
   
    if (!$input) {
      return new JsonResponse($results);
    }

    /** @var \GuzzleHttp\Client $client */
    $client = \Drupal::service('http_client_factory')->fromOptions([
      'base_uri' => 'https://api-adresse.data.gouv.fr/',
    ]);

    $response = $client->get('search', [
      'query' => [
        'q' => $input,
        'limit' => 20,
      ]
    ]);

    $datas = Json::decode($response->getBody());
    $items = [];
    $villes = (isset($datas['features']) ? $datas['features'] : NULL);
    if ($villes) {
      foreach ($villes as $ville) {
        $results[] = [
          'value' => $ville['properties']['name'] . ' * ' . $ville['properties']['city'] . ' * ' .$ville['properties']['postcode'],
          'label' => $ville['properties']['label'],
        ];
      }
    }
    return new JsonResponse($results);
  }
}