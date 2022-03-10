<?php

/**
 * @file
 * Contains \Drupal\gm_adhesion\Form\DonateForm.
 */

namespace Drupal\gm_adhesion\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gm_adhesion\MgSystemInterface;

use Drupal\Core\Render\Element\Textfield;

class DonateForm extends FormBase {

  /**
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $temp_store_factory;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * @var \Drupal\Core\TempStore\PrivateTempStore
   */
  protected $store;

  /**
   * @var \Drupal\gm_adhesion\MgSystemInterface $mg
   */
  protected $mg;

  /**
   * Constructs a \Drupal\demo\Form\DonStart\DonStartFormBase.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   * @param \Drupal\Core\Session\AccountInterface $current_user
   * @param \Drupal\gm_adhesion\MgSystemInterface $mg
   *   The BrrSystemInterface to user custom function
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user, MgSystemInterface $mg) {
    $this->tempStoreFactory = $temp_store_factory->get('gm_adhesion');
    $this->sessionManager = $session_manager;
    $this->currentUser = $current_user;
    $this->mg = $mg;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('session_manager'),
      $container->get('current_user'),
      $container->get('gm_adhesion.helpers_system')
    );
  }

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'gm_donate_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Start a manual session for anonymous users.
    if ($this->currentUser->isAnonymous() && !isset($_SESSION['gm_adhesion_form_holds_session'])) {
      $_SESSION['gm_adhesion_form_holds_session'] = true;
      $this->sessionManager->start();
    }

    $form = array();

    $config = \Drupal::config('gm_adhesion.settings');
    // ksm($config);
    $paypal_settings = $config->get('paypal');

    

    // ksm($paypal_settings);

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t("Vous êtes une majorité à donner 50€"),
			'#prefix' => '<div id="gm-description">',
			'#suffix' => '</div>',
    ];

    $form['type'] = array(
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
    );

		$form['type']['donation_type'] = [
			'#type' => 'radios',
			// '#title' => $this->t('Type'),
			'#options' => array(
				1 => $this->t('Punctual'),
				2 => $this->t('Mental'),
			),
			'#default_value' => 1,
			'#prefix' => '<div id="gm-type">',
			'#suffix' => '</div>',
      '#required' => TRUE,
		];

    if (isset($paypal_settings['donation_ponctuel'])) {

      $donation_ponctuel_options = [];
      $donation_ponctuel_element = $paypal_settings['donation_ponctuel'];

      $form['ponctuel'] = array(
        '#type' => 'fieldset',
        '#collapsible' => FALSE,
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'visible' => [
            ':input[name="donation_type"]' => ['value' => 1],
          ],
        ],
      );

      foreach ($donation_ponctuel_element as $key => $value) {
        if ($value['name'] != '' && $value['amount'] != '' ) {
          $donation_ponctuel_options[$value['amount']] = $value['name'] . ' €';
        }
      }

      $form['ponctuel']['amount_list_ponctuel'] = [
        '#type' => 'radios',
        // '#title' => $this->t('montant'),
        '#options' => $donation_ponctuel_options,
        '#default_value' => isset($donation_ponctuel_options[50]) ? 50 : NULL,
        '#prefix' => '<div id="gm-amount">',
        '#suffix' => '</div>',
      ];

    }

    if (isset($paypal_settings['donation_mensuel'])) {

      $donation_mensuel_options = [];
      $donation_mensuel_element = $paypal_settings['donation_mensuel'];

      $form['mensuel'] = array(
        '#type' => 'fieldset',
        '#collapsible' => FALSE,
        // '#title' => $this->t('Author'),
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'visible' => [
            ':input[name="donation_type"]' => ['value' => 2],
          ],
        ],
      );

      foreach ($donation_mensuel_element as $key => $value) {
        if ($value['name'] != '' && $value['code'] != '' ) {
          $donation_mensuel_options[$value['code']] = $value['name'] . ' €';
        }
      }

      $form['mensuel']['amount_list_mensuel'] = [
      	'#type' => 'radios',
      	// '#title' => $this->t('montant'),
      	'#options' => $donation_mensuel_options,
        '#default_value' => isset($donation_mensuel_options[50]) ? 50 : NULL,
      	'#prefix' => '<div id="gm-amount">',
      	'#suffix' => '</div>',
      ];

    }

    $form['others'] = array(
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
      // '#title' => $this->t('Author'),
      '#states' => [
        'visible' =>[
          [
            ':input[name="amount_list_ponctuel"]' => ['value' => 0],
            'and',
            ':input[name="donation_type"]' => ['value' => 1],
          ],
          'or',
          [
            ':input[name="amount_list_mensuel"]' => ['value' => 0],
            'and',
            ':input[name="donation_type"]' => ['value' => 2],
          ],
        ],
      ],
    );

		// autre_montant.
		$form['others']['autre_montant'] = array(
			'#type' => 'number',
      '#title' => $this->t('Other amount ...'),
			'#attributes' => array(
        // 'placeholder' => t('Autre montant...'),
      ),
			'#prefix' => '<div id="gm-autre">',
			'#suffix' => '</div>',
		);


    $form['donateur'] = array(
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
      '#prefix' => '<div id="gm-donateur-all">',
			'#suffix' => '</div>',
    );

		$form['donateur']['donateur_genre'] = [
			'#type' => 'radios',
			// '#title' => $this->t('Type'),
			'#options' => array(
				'femme' => $this->t('Mrs'),
				'homme' => $this->t('Mr.'),
        'autre' => $this->t('Other'),
			),
			'#default_value' => 'autre',
      '#required' => TRUE,
			'#prefix' => '<div id="gm-donateur-genre">',
			'#suffix' => '</div>',
		];

    $form['donateur']['donateur_prenom'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Forname'),
      '#required' => TRUE,
      '#default_value' => '',
      '#prefix' => '<div id="gm-donateur-prenom">',
			'#suffix' => '</div>',
      '#attributes' => array(
        'autocomplete' => 'nope',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
    ];

    $form['donateur']['donateur_nom'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Name'),
      '#default_value' => '',
      '#prefix' => '<div id="gm-donateur-nom">',
			'#suffix' => '</div>',
      '#attributes' => array(
        'autocomplete' => 'nope',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
    ];

    $form['donateur']['donateur_address'] = [
      '#type' => 'textfield',
      '#title' => t('Address'),
      '#autocomplete_route_name' => 'gm_adhesion.find_adresse',
      '#prefix' => '<div id="gm-donateur-adresse">',
			'#suffix' => '</div>',
      '#description' => '<div class="ripple"></div>',
      '#required' => TRUE,
      // '#attached' => [
      //   'library' => [
      //     'gm_adhesion/gm-autocomplete'
      //   ],
      // ],
      '#attributes' => array(
        'autocomplete' => 'new-password',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
    ];

    $nationalite = $this->mg->getCountryOptions();

    $form['donateur']['donateur_nationalite'] = [
      '#title' => $this->t('Nationality'),
      '#required' => TRUE,
      '#type' => 'select',
      '#default_value' => 'fr',
      '#options' => $nationalite,
      '#prefix' => '<div id="gm-donateur-nationalite">',
			'#suffix' => '</div>',
      // '#empty_option' => t('- Nationalité -'),
      // '#empty_value' => '_none',
    ];

    $form['donateur']['donateur_email'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#title' => $this->t('Email'),
      '#default_value' => '',
      '#prefix' => '<div id="gm-donateur-email">',
			'#suffix' => '</div>',
      '#attributes' => array(
        'autocomplete' => 'new-password',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
    ];

    $personne_morale = isset($paypal_settings['personne_morale']) && isset($paypal_settings['personne_morale']['value']) ? $paypal_settings['personne_morale']['value'] : '';
		if ($personne_morale) {
			$form['honneur'] = array(
				'#type' => 'checkbox',
				'#title' => $this->t($personne_morale),
        '#prefix' => '<div id="gm-honneur">',
			  '#suffix' => '</div>',
        '#required' => TRUE,
			);
		}

    $personne_nationalite = isset($paypal_settings['personne_nationalite']) && isset($paypal_settings['personne_nationalite']['value']) ? $paypal_settings['personne_nationalite']['value'] : '';
		if ($personne_nationalite) {
			$form['nationalite'] = array(
				'#type' => 'checkbox',
				'#title' => $this->t($personne_nationalite),
        '#prefix' => '<div id="gm-nationalite">',
			  '#suffix' => '</div>',
        '#required' => TRUE,
			);
		}

    $personne_mentions = isset($paypal_settings['personne_mentions']) && isset($paypal_settings['personne_mentions']['value']) ? $paypal_settings['personne_mentions']['value'] : '';
		if ($personne_mentions) {
			$form['information'] = array(
				'#type' => 'checkbox',
				'#title' => $this->t($personne_mentions),
        '#prefix' => '<div id="gm-information">',
			  '#suffix' => '</div>',
        '#required' => TRUE,
			);
		}

    $form['#attached']['library'][] = 'gm_adhesion/gm-donation-settings';

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Validate my donation'),
      '#button_type' => 'primary',
      '#weight' => 10,
      '#prefix' => '<div id="gm-action">',
			'#suffix' => '</div>',
    );

    return $form;
  }

  /**
   * Validate the title and the checkbox of the form
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * 
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $donation_type = $form_state->getValue('donation_type');
		$autre_montant = $form_state->getValue('autre_montant');
    $amount_list_ponctuel = $form_state->getValue('amount_list_ponctuel');
    $donateur_address = $form_state->getValue('donateur_address');

		if ($donation_type == '1' && $amount_list_ponctuel == '1' && empty($autre_montant)) {
			$form_state->setErrorByName('autre_montant', $this->t('Merci d\'indiquez un autre montant pour finaliser le processus de donation.'));
		}

		if ($donation_type == '1' && $amount_list_ponctuel == '1' && $autre_montant <= 4) {
			$form_state->setErrorByName('autre_montant', $this->t('La somme minimum de don est de 5 euros.'));
		}

    if(!strpos($donateur_address, ' * ') !== false){
      $form_state->setErrorByName('donateur_address', $this->t('L\'adresse fournie n\'est pas formatter correctement, veuillez taper le nom de votre adresse, puis selectionner une adresse proposée dans la liste.'));
    }

  }

    /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $donation_mensuel_amount = '';
    // Get Mensul donation amount
    if ($form_state->getValue('donation_type') == '2') {
      $config = \Drupal::config('gm_adhesion.settings');
      $paypal_settings = $config->get('paypal');
      if (isset($paypal_settings['donation_mensuel'])) {
        $donation_mensuel_element = $paypal_settings['donation_mensuel'];
        foreach ($donation_mensuel_element as $key => $value) {
          if ($value['code'] == $form_state->getValue('amount_list_mensuel') ) {
            $donation_mensuel_amount = $value['name'];
          }
        }
      }
    }elseif($form_state->getValue('donation_type') == '1' && $form_state->getValue('amount_list_ponctuel') == '0') {
      $donation_mensuel_amount = $form_state->getValue('autre_montant');
    }else{
      $donation_mensuel_amount = $form_state->getValue('amount_list_ponctuel');
    }

    // Process adress
    $adresse_street = NULL;
    $adresse_city = NULL;
    $adresse_code = NULL;
    $adresse = ($form_state->getValue('donateur_address') !== NULL) ? $form_state->getValue('donateur_address') : NULL;
    if ($adresse) {
      $drss = explode(" * ", $adresse);
      $adresse_street = $drss[0];
      $adresse_city = $drss[1];
      $adresse_code = $drss[2];
    }

    // Information donateur
    $portrait_donateur = [
      'genre' => $form_state->getValue('donateur_genre'),
      'prenom' => $form_state->getValue('donateur_prenom'),
      'nom' => $form_state->getValue('donateur_nom'),
      'nationalite' => $form_state->getValue('donateur_nationalite'),
      'email' => $form_state->getValue('donateur_email'),
      'genre' => ($form_state->getValue('donateur_genre') !== NULL) ? $form_state->getValue('donateur_genre') : NULL,
      'street' => $adresse_street,
      'city' => $adresse_city,
      'postal_code' => $adresse_code,
    ];

    $this->tempStoreFactory->set('portrait', $portrait_donateur);
    $this->tempStoreFactory->set('donation_amount', $donation_mensuel_amount);
    $this->tempStoreFactory->set('donation_type_p', $form_state->getValue('donation_type'));
    $this->tempStoreFactory->set('amount_list_ponctuel', $form_state->getValue('amount_list_ponctuel'));
    $this->tempStoreFactory->set('amount_list_mensuel', $form_state->getValue('amount_list_mensuel'));
    $this->tempStoreFactory->set('autre_montant', $form_state->getValue('autre_montant'));
    $this->tempStoreFactory->set('honneur', $form_state->getValue('honneur'));
    $this->tempStoreFactory->set('nationalite', $form_state->getValue('nationalite'));
    $this->tempStoreFactory->set('information', $form_state->getValue('information'));
    // Save the data
    // parent::saveData();
    $form_state->setRedirect('gm_adhesion.donner_form_validation');
  }


  


  // /**
  //  * Saves the data from the donstart form.
  //  */
  // protected function saveData() {
  //   // Logic for saving data goes here...
  //   // $this->deleteStore();
  //   // drupal_set_message($this->t('The form has been saved.'));

  // }

  /**
   * Helper method that removes all the keys from the store collection used for
   * the donstart form.
   */
  // protected function deleteStore() {
  //   $keys = ['description', 'description_eco', 'autre_montant', 'donation_type'];
  //   foreach ($keys as $key) {
  //     $this->tempStoreFactory->delete($key);
  //   }
  // }
}