<?php

namespace Drupal\gm_adhesion\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

class AdhesionForm extends FormBase {

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
    return 'gm_adhesion_form';
  }

	/**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

		$config = \Drupal::config('gm_adhesion.settings');
    $adhesion = $config->get('adhesion');

		$form['information'] = [
			'#type' => 'fieldset',
			// '#title' => $this->t("Informations personnelles"),
			'#tree' => TRUE,
			'#prefix' => '<div id="gm-fieldset-information">',
			'#suffix' => '</div>',
		];

		$form['information']['prenom'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Forname'),
			// '#description' => $this->t(''),
			'#required' => TRUE,
			'#attributes' => array(
        'autocomplete' => 'nope',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
		];

		$form['information']['nom'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Name'),
			// '#description' => $this->t('Hello'),
			'#default_value' => 'Hello',
			'#required' => TRUE,
			'#attributes' => array(
        'autocomplete' => 'nope',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
		];

		$form['connexion'] = [
			'#type' => 'fieldset',
			// '#title' => $this->t("Informations personnelles"),
			'#tree' => TRUE,
			'#prefix' => '<div id="gm-fieldset-connexion">',
			'#suffix' => '</div>',
		];

		$form['connexion']['email'] = [
			'#type' => 'email',
			'#title' => $this->t('Email'),
			// '#description' => $this->t(''),
			'#required' => TRUE,
			'#attributes' => array(
        'autocomplete' => 'nope',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
		];

		$form['connexion']['password'] = [
			'#type' => 'password',
			'#title' => $this->t('Password'),
			// '#description' => $this->t(''),
			'#required' => TRUE,
			'#attributes' => array(
        'autocomplete' => 'nope',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
		];

		$form['user'] = [
			'#type' => 'fieldset',
			// '#title' => $this->t("Informations personnelles"),
			'#tree' => TRUE,
			'#prefix' => '<div id="gm-fieldset-user">',
			'#suffix' => '</div>',
		];

		$form['user']['nationalite'] = array(
			'#type' => 'select',
			'#title' => t('Nationality'),
			'#options' => array(
				'fr' => t('Française'),
				'ch' => t('Suisse'),
				'be' => t('Belge'),
				'de' => t('Allemande'),
				'it' => t('Italienne'),
				'af' => t('Afghane'),
				'al' => t('Albanaise'),
				'dz' => t('Algerienne'),
				'us' => t('Americaine'),
				'ad' => t('Andorrane'),
				'ao' => t('Angolaise'),
				'ag' => t('Antiguaise et barbudienne'),
				'ar' => t('Argentine'),
				'am' => t('Armenienne'),
				'au' => t('Australienne'),
				'at' => t('Autrichienne'),
				'az' => t('Azerbaïdjanaise'),
				'bs' => t('Bahamienne'),
				'bh' => t('Bahreinienne'),
				'bd' => t('Bangladaise'),
				'bb' => t('Barbadienne'),
				'bz' => t('Belizienne'),
				'bj' => t('Beninoise'),
				'bt' => t('Bhoutanaise'),
				'by' => t('Bielorusse'),
				'mm' => t('Birmane'),
				'gw' => t('Bissau-Guinéenne'),
				'bo' => t('Bolivienne'),
				'ba' => t('Bosnienne'),
				'bw' => t('Botswanaise'),
				'br' => t('Bresilienne'),
				'uk' => t('Britannique'),
				'bn' => t('Bruneienne'),
				'bg' => t('Bulgare'),
				'bf' => t('Burkinabe'),
				'bi' => t('Burundaise'),
				'kh' => t('Cambodgienne'),
				'cm' => t('Camerounaise'),
				'ca' => t('Canadienne'),
				'cv' => t('Cap-verdienne'),
				'cf' => t('Centrafricaine'),
				'cl' => t('Chilienne'),
				'cn' => t('Chinoise'),
				'cy' => t('Chypriote'),
				'co' => t('Colombienne'),
				'km' => t('Comorienne'),
				'cg' => t('Congolaise'),
				'cr' => t('Costaricaine'),
				'hr' => t('Croate'),
				'cu' => t('Cubaine'),
				'dk' => t('Danoise'),
				'dj' => t('Djiboutienne'),
				'do' => t('Dominicaine'),
				'dm' => t('Dominiquaise'),
				'eg' => t('Egyptienne'),
				'ae' => t('Emirienne'),
				'gq' => t('Equato-guineenne'),
				'ec' => t('Equatorienne'),
				'er' => t('Erythreenne'),
				'es' => t('Espagnole'),
				'tl' => t('Est-timoraise'),
				'ee' => t('Estonienne'),
				'et' => t('Ethiopienne'),
				'fj' => t('Fidjienne'),
				'fi' => t('Finlandaise'),
				'ga' => t('Gabonaise'),
				'gm' => t('Gambienne'),
				'ge' => t('Georgienne'),
				'gh' => t('Ghaneenne'),
				'gd' => t('Grenadienne'),
				'gt' => t('Guatemalteque'),
				'gn' => t('Guineenne'),
				'gf' => t('Guyanienne'),
				'ht' => t('Haïtienne'),
				'gr' => t('Hellenique'),
				'hn' => t('Hondurienne'),
				'hu' => t('Hongroise'),
				'in' => t('Indienne'),
				'id' => t('Indonesienne'),
				'iq' => t('Irakienne'),
				'ie' => t('Irlandaise'),
				'is' => t('Islandaise'),
				'il' => t('Israélienne'),
				'ci' => t('Ivoirienne'),
				'jm' => t('Jamaïcaine'),
				'jp' => t('Japonaise'),
				'jo' => t('Jordanienne'),
				'kz' => t('Kazakhstanaise'),
				'ke' => t('Kenyane'),
				'kg' => t('Kirghize'),
				'ki' => t('Kiribatienne'),
				'kn' => t('Kittitienne-et-nevicienne'),
				'xk​' => t('Kossovienne'),
				'kw' => t('Koweitienne'),
				'la' => t('Laotienne'),
				'ls' => t('Lesothane'),
				'lv' => t('Lettone'),
				'lb' => t('Libanaise'),
				'lr' => t('Liberienne'),
				'ly' => t('Libyenne'),
				'li' => t('Liechtensteinoise'),
				'lt' => t('Lituanienne'),
				'lu' => t('Luxembourgeoise'),
				'mk' => t('Macedonienne'),
				'my' => t('Malaisienne'),
				'mw' => t('Malawienne'),
				'mv' => t('Maldivienne'),
				'mg' => t('Malgache'),
				'ml' => t('Malienne'),
				'mt' => t('Maltaise'),
				'ma' => t('Marocaine'),
				'mh' => t('Marshallaise'),
				'mu' => t('Mauricienne'),
				'mr' => t('Mauritanienne'),
				'mx' => t('Mexicaine'),
				'fm' => t('Micronesienne'),
				'md' => t('Moldave'),
				'mc' => t('Monegasque'),
				'mn' => t('Mongole'),
				'me' => t('Montenegrine'),
				'mz' => t('Mozambicaine'),
				'na' => t('Namibienne'),
				'nr' => t('Nauruane'),
				'nl' => t('Neerlandaise'),
				'nz' => t('Neo-zelandaise'),
				'np' => t('Nepalaise'),
				'ni' => t('Nicaraguayenne'),
				'ng' => t('Nigeriane'),
				'ne' => t('Nigerienne'),
				'kp' => t('Nord-coréenne'),
				'no' => t('Norvegienne'),
				'om' => t('Omanaise'),
				'ug' => t('Ougandaise'),
				'uz' => t('Ouzbeke'),
				'pk' => t('Pakistanaise'),
				'pw' => t('Palau'),
				'ps' => t('Palestinienne'),
				'pa' => t('Panameenne'),
				'pg' => t('Papouane-neoguineenne'),
				'py' => t('Paraguayenne'),
				'pe' => t('Peruvienne'),
				'ph' => t('Philippine'),
				'pl' => t('Polonaise'),
				'pr' => t('Portoricaine'),
				'pt' => t('Portugaise'),
				'qa' => t('Qatarienne'),
				'ro' => t('Roumaine'),
				'ru' => t('Russe'),
				'rw' => t('Rwandaise'),
				'lc' => t('Saint-Lucienne'),
				'sm' => t('Saint-Marinaise'),
				'vc' => t('Saint-Vincentaise-et-Grenadine'),
				'sb' => t('Salomonaise'),
				'sv' => t('Salvadorienne'),
				'ws' => t('Samoane'),
				'st' => t('Santomeenne'),
				'sa' => t('Saoudienne'),
				'sn' => t('Senegalaise'),
				'rs' => t('Serbe'),
				'sc' => t('Seychelloise'),
				'sl' => t('Sierra-leonaise'),
				'sg' => t('Singapourienne'),
				'sk' => t('Slovaque'),
				'si' => t('Slovene'),
				'so' => t('Somalienne'),
				'sd' => t('Soudanaise'),
				'lk' => t('Sri-lankaise'),
				'za' => t('Sud-africaine'),
				'kr' => t('Sud-coréenne'),
				'se' => t('Suedoise'),
				'sr' => t('Surinamaise'),
				'ze' => t('Swazie'),
				'sy' => t('Syrienne'),
				'tj' => t('Tadjike'),
				'tw' => t('Taiwanaise'),
				'tz' => t('Tanzanienne'),
				'td' => t('Tchadienne'),
				'cz' => t('Tcheque'),
				'th' => t('Thaïlandaise'),
				'tg' => t('Togolaise'),
				'tg' => t('Tonguienne'),
				'tt' => t('Trinidadienne'),
				'tn' => t('Tunisienne'),
				'tm' => t('Turkmene'),
				'tr' => t('Turque'),
				'tv' => t('Tuvaluane'),
				'ua' => t('Ukrainienne'),
				'uy' => t('Uruguayenne'),
				'vu' => t('Vanuatuane'),
				've' => t('Venezuelienne'),
				'vn' => t('Vietnamienne'),
				'ye' => t('Yemenite'),
				'zm' => t('Zambienne'),
				'zw' => t('Zimbabweenne'),
			),
			'#default_value' => 'fr',
		);

		// Date.
		$date_format = 'd-m-Y';
    $form['user']['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Anniversary'),
			'#required' => TRUE,
			// '#attributes' => array(
      //   'placeholder' => t('Anniversaire'),
      // ),
			'#date_date_format' => $date_format,
    ];

		$form['user']['gender'] = array(
			'#type' => 'select',
			'#title' => $this->t('Gender'),
			'#options' => array(
				0 => $this->t('Je suis...'),
				'femme' => $this->t('Une femme'),
				'homme' => $this->t('Un homme'),
				'autre' => $this->t('Un autre'),
			),
			'#default_value' => NULL,
		);

		$form['locality'] = [
      '#type' => 'fieldset',
      // '#title' => $this->t("Informations personnelles"),
      '#tree' => TRUE,
			'#prefix' => '<div id="gm-fieldset-locality">',
			'#suffix' => '</div>',
    ];

		// $form['locality']['address'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Adresse'),
    //   // '#description' => $this->t(''),
    //   '#required' => TRUE,
		// 	'#default_value' => '110 rue orfila 75020 Paris',
    // ];

		$form['locality']['address'] = [
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
			// '#attributes' => array(
      //   'placeholder' => t('Selectionner une adresse dans la liste...'),
      // ),
			'#attributes' => array(
        'autocomplete' => 'new-password',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
    ];

		$form['num'] = [
      '#type' => 'fieldset',
      // '#title' => $this->t("Numéro de téléphone"),
      '#tree' => TRUE,
			'#prefix' => '<div id="gm-fieldset-num">',
			'#suffix' => '</div>',
    ];

		$form['num']['indicatif'] = array(
			'#type' => 'select',
			'#title' => $this->t('Indicative'),
			'#options' => array(
				'44' => 'UK (+44)',
				'1' => 'USA (+1)',
				'213' => 'Algeria (+213)',
				'376' => 'Andorra (+376)',
				'244' => 'Angola (+244)',
				'1264' => 'Anguilla (+1264)',
				'1268' => 'Antigua & Barbuda (+1268)',
				'54' => 'Argentina (+54)',
				'374' => 'Armenia (+374)',
				'297' => 'Aruba (+297)',
				'61' => 'Australia (+61)',
				'43' => 'Austria (+43)',
				'994' => 'Azerbaijan (+994)',
				'1242' => 'Bahamas (+1242)',
				'973' => 'Bahrain (+973)',
				'880' => 'Bangladesh (+880)',
				'1246' => 'Barbados (+1246)',
				'375' => 'Belarus (+375)',
				'32' => 'Belgium (+32)',
				'501' => 'Belize (+501)',
				'229' => 'Benin (+229)',
				'1441' => 'Bermuda (+1441)',
				'975' => 'Bhutan (+975)',
				'591' => 'Bolivia (+591)',
				'387' => 'Bosnia Herzegovina (+387)',
				'267' => 'Botswana (+267)',
				'55' => 'Brazil (+55)',
				'673' => 'Brunei (+673)',
				'359' => 'Bulgaria (+359)',
				'226' => 'Burkina Faso (+226)',
				'257' => 'Burundi (+257)',
				'855' => 'Cambodia (+855)',
				'237' => 'Cameroon (+237)',
				'1' => 'Canada (+1)',
				'238' => 'Cape Verde Islands (+238)',
				'1345' => 'Cayman Islands (+1345)',
				'236' => 'Central African Republic (+236)',
				'56' => 'Chile (+56)',
				'86' => 'China (+86)',
				'57' => 'Colombia (+57)',
				'269' => 'Comoros (+269)',
				'242' => 'Congo (+242)',
				'682' => 'Cook Islands (+682)',
				'506' => 'Costa Rica (+506)',
				'385' => 'Croatia (+385)',
				'53' => 'Cuba (+53)',
				'90392' => 'Cyprus North (+90392)',
				'357' => 'Cyprus South (+357)',
				'42' => 'Czech Republic (+42)',
				'45' => 'Denmark (+45)',
				'253' => 'Djibouti (+253)',
				'1809' => 'Dominica (+1809)',
				'1809' => 'Dominican Republic (+1809)',
				'593' => 'Ecuador (+593)',
				'20' => 'Egypt (+20)',
				'503' => 'El Salvador (+503)',
				'240' => 'Equatorial Guinea (+240)',
				'291' => 'Eritrea (+291)',
				'372' => 'Estonia (+372)',
				'251' => 'Ethiopia (+251)',
				'500' => 'Falkland Islands (+500)',
				'298' => 'Faroe Islands (+298)',
				'679' => 'Fiji (+679)',
				'358' => 'Finland (+358)',
				'33' => 'France (+33)',
				'594' => 'French Guiana (+594)',
				'689' => 'French Polynesia (+689)',
				'241' => 'Gabon (+241)',
				'220' => 'Gambia (+220)',
				'7880' => 'Georgia (+7880)',
				'49' => 'Germany (+49)',
				'233' => 'Ghana (+233)',
				'350' => 'Gibraltar (+350)',
				'30' => 'Greece (+30)',
				'299' => 'Greenland (+299)',
				'1473' => 'Grenada (+1473)',
				'590' => 'Guadeloupe (+590)',
				'671' => 'Guam (+671)',
				'502' => 'Guatemala (+502)',
				'224' => 'Guinea (+224)',
				'245' => 'Guinea - Bissau (+245)',
				'592' => 'Guyana (+592)',
				'509' => 'Haiti (+509)',
				'504' => 'Honduras (+504)',
				'852' => 'Hong Kong (+852)',
				'36' => 'Hungary (+36)',
				'354' => 'Iceland (+354)',
				'91' => 'India (+91)',
				'62' => 'Indonesia (+62)',
				'98' => 'Iran (+98)',
				'964' => 'Iraq (+964)',
				'353' => 'Ireland (+353)',
				'972' => 'Israel (+972)',
				'39' => 'Italy (+39)',
				'1876' => 'Jamaica (+1876)',
				'81' => 'Japan (+81)',
				'962' => 'Jordan (+962)',
				'7' => 'Kazakhstan (+7)',
				'254' => 'Kenya (+254)',
				'686' => 'Kiribati (+686)',
				'850' => 'Korea North (+850)',
				'82' => 'Korea South (+82)',
				'965' => 'Kuwait (+965)',
				'996' => 'Kyrgyzstan (+996)',
				'856' => 'Laos (+856)',
				'371' => 'Latvia (+371)',
				'961' => 'Lebanon (+961)',
				'266' => 'Lesotho (+266)',
				'231' => 'Liberia (+231)',
				'218' => 'Libya (+218)',
				'417' => 'Liechtenstein (+417)',
				'370' => 'Lithuania (+370)',
				'352' => 'Luxembourg (+352)',
				'853' => 'Macao (+853)',
				'389' => 'Macedonia (+389)',
				'261' => 'Madagascar (+261)',
				'265' => 'Malawi (+265)',
				'60' => 'Malaysia (+60)',
				'960' => 'Maldives (+960)',
				'223' => 'Mali (+223)',
				'356' => 'Malta (+356)',
				'692' => 'Marshall Islands (+692)',
				'596' => 'Martinique (+596)',
				'222' => 'Mauritania (+222)',
				'269' => 'Mayotte (+269)',
				'52' => 'Mexico (+52)',
				'691' => 'Micronesia (+691)',
				'373' => 'Moldova (+373)',
				'377' => 'Monaco (+377)',
				'976' => 'Mongolia (+976)',
				'1664' => 'Montserrat (+1664)',
				'212' => 'Morocco (+212)',
				'258' => 'Mozambique (+258)',
				'95' => 'Myanmar (+95)',
				'264' => 'Namibia (+264)',
				'674' => 'Nauru (+674)',
				'977' => 'Nepal (+977)',
				'31' => 'Netherlands (+31)',
				'687' => 'New Caledonia (+687)',
				'64' => 'New Zealand (+64)',
				'505' => 'Nicaragua (+505)',
				'227' => 'Niger (+227)',
				'234' => 'Nigeria (+234)',
				'683' => 'Niue (+683)',
				'672' => 'Norfolk Islands (+672)',
				'670' => 'Northern Marianas (+670)',
				'47' => 'Norway (+47)',
				'968' => 'Oman (+968)',
				'680' => 'Palau (+680)',
				'507' => 'Panama (+507)',
				'675' => 'Papua New Guinea (+675)',
				'595' => 'Paraguay (+595)',
				'51' => 'Peru (+51)',
				'63' => 'Philippines (+63)',
				'48' => 'Poland (+48)',
				'351' => 'Portugal (+351)',
				'1787' => 'Puerto Rico (+1787)',
				'974' => 'Qatar (+974)',
				'262' => 'Reunion (+262)',
				'40' => 'Romania (+40)',
				'7' => 'Russia (+7)',
				'250' => 'Rwanda (+250)',
				'378' => 'San Marino (+378)',
				'239' => 'Sao Tome & Principe (+239)',
				'966' => 'Saudi Arabia (+966)',
				'221' => 'Senegal (+221)',
				'381' => 'Serbia (+381)',
				'248' => 'Seychelles (+248)',
				'232' => 'Sierra Leone (+232)',
				'65' => 'Singapore (+65)',
				'421' => 'Slovak Republic (+421)',
				'386' => 'Slovenia (+386)',
				'677' => 'Solomon Islands (+677)',
				'252' => 'Somalia (+252)',
				'27' => 'South Africa (+27)',
				'34' => 'Spain (+34)',
				'94' => 'Sri Lanka (+94)',
				'290' => 'St. Helena (+290)',
				'1869' => 'St. Kitts (+1869)',
				'1758' => 'St. Lucia (+1758)',
				'249' => 'Sudan (+249)',
				'597' => 'Suriname (+597)',
				'268' => 'Swaziland (+268)',
				'46' => 'Sweden (+46)',
				'41' => 'Switzerland (+41)',
				'963' => 'Syria (+963)',
				'886' => 'Taiwan (+886)',
				'7' => 'Tajikstan (+7)',
				'66' => 'Thailand (+66)',
				'228' => 'Togo (+228)',
				'676' => 'Tonga (+676)',
				'1868' => 'Trinidad & Tobago (+1868)',
				'216' => 'Tunisia (+216)',
				'90' => 'Turkey (+90)',
				'7' => 'Turkmenistan (+7)',
				'993' => 'Turkmenistan (+993)',
				'1649' => 'Turks & Caicos Islands (+1649)',
				'688' => 'Tuvalu (+688)',
				'256' => 'Uganda (+256)',
				'380' => 'Ukraine (+380)',
				'971' => 'United Arab Emirates (+971)',
				'598' => 'Uruguay (+598)',
				'7' => 'Uzbekistan (+7)',
				'678' => 'Vanuatu (+678)',
				'379' => 'Vatican City (+379)',
				'58' => 'Venezuela (+58)',
				'84' => 'Vietnam (+84)',
				'84' => 'Virgin Islands - British (+1284)',
				'84' => 'Virgin Islands - US (+1340)',
				'681' => 'Wallis & Futuna (+681)',
				'969' => 'Yemen (North)(+969)',
				'967' => 'Yemen (South)(+967)',
				'260' => 'Zambia (+260)',
				'263' => 'Zimbabwe (+263)',
			),
			'#default_value' => [33],
			// '#required' => TRUE,
		);

		$form['num']['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number'),
      // '#description' => $this->t(''),
      // '#required' => TRUE,
			'#attributes' => array(
        'autocomplete' => 'nope',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
    ];

		$form['accept'] = [
      '#type' => 'fieldset',
      // '#title' => $this->t("Accept"),
      '#tree' => TRUE,
			'#prefix' => '<div id="gm-fieldset-accept">',
			'#suffix' => '</div>',
    ];

    $form['accept']['elu'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('I am elected'),
    );
		$site_name = \Drupal::config('system.site')->get('name');
		$defaut_charte = t('Yes, I adhere to the charter of values, the statutes and the operating rules') . $site_name;
		$adhesion_charte = isset($adhesion['adhesion_charte']) && isset($adhesion['adhesion_charte']['value']) ? $adhesion['adhesion_charte']['value'] : $defaut_charte;

		if ($adhesion_charte) {
			$form['accept']['jadhere'] = array(
				'#type' => 'checkbox',
				'#title' => $this->t($adhesion_charte),
				'#required' => TRUE,
			);
		}

		$adhesion_etre_informe = isset($adhesion['adhesion_etre_informe']) && isset($adhesion['adhesion_etre_informe']['value']) ? $adhesion['adhesion_etre_informe']['value'] : '';
		if ($adhesion_etre_informe) {
			$form['accept']['actualite'] = array(
				'#type' => 'checkbox',
				'#title' => $this->t($adhesion_etre_informe),
			);
		}	

		$adhesion_reception = isset($adhesion['adhesion_reception']) && isset($adhesion['adhesion_reception']['value']) ? $adhesion['adhesion_reception']['value'] : '';
		if ($adhesion_reception) {
			$form['accept']['informations'] = array(
				'#type' => 'checkbox',
				'#title' => $adhesion_reception,
			);
		}
		

		$form['#attached']['library'][] = 'gm_adhesion/gm-design-adhesion';

    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send my membership'),
			// '#weight' => -15,
    ];

		$adhesion_donnee = isset($adhesion['adhesion_donnee']) && isset($adhesion['adhesion_donnee']['value']) ? $adhesion['adhesion_donnee']['value'] : '';
		$form['description'] = [
      '#type' => 'item',
      '#markup' => $adhesion_donnee,
			'#weight' => 101,
    ];

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

    // $nom = $form_state->getValue('nom');
		// $prénom = $form_state->getValue('prénom');
		// Process adress
		$adresse_street = NULL;
		$adresse_city = NULL;
		$adresse_code = NULL;
		$field_adresse = $form_state->getValue('locality')['address'];

		if (!$field_adresse) {
			$form_state->setErrorByName('address', $this->t('Désolé, nous avons besoin d\'une ADRESSE française valide. Une rue, une ville et un code postale.'));
		}

		$drss = explode(" * ", $field_adresse);
		$adresse_street = $drss[0];
		$adresse_city = $drss[1];
		$adresse_code = $drss[2];

		// chekc part of adresse
		if (!isset($drss[0]) || !isset($drss[1]) || !isset($drss[2])) {
			$form_state->setErrorByName('address', $this->t('Désolé, nous avons besoin d\'une ADRESSE française valide. Une rue, une ville et un code postale.'));
		}

		// Chekcpostal code
		if (!is_int((int)$drss[2]) || strlen($drss[2]) != 5) {
			$form_state->setErrorByName('address', $this->t('Désolé, nous avons besoin d\'un CODE POSTAL français valide.'));
		}
  }

	/**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

		$nom = $form_state->getValue('information')['nom'];
		$prenom = $form_state->getValue('information')['prenom'];

		$email = $form_state->getValue('connexion')['email'];
		$password = $form_state->getValue('connexion')['password'];

		$field_nationalite = $form_state->getValue('user')['nationalite'];
		$field_date_de_naissance = $form_state->getValue('user')['date'];
		$field_genre = isset($form_state->getValue('user')['gender']) ? $form_state->getValue('user')['gender'] : NULL;

		$field_adresse = $form_state->getValue('locality')['address'];

		$field_telephone_index = isset($form_state->getValue('num')['indicatif']) ? $form_state->getValue('num')['indicatif'] : NULL;
		$field_telephone = isset($form_state->getValue('num')['phone']) ? $form_state->getValue('num')['phone'] : NULL;

		$field_je_suis_elu = $form_state->getValue('accept')['elu'];
		$field_j_adhere = $form_state->getValue('accept')['jadhere'];
		$field_information_par_email = $form_state->getValue('accept')['actualite'];
		$field_information_sms = $form_state->getValue('accept')['informations'];

		$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
		$username = $prenom . ' ' . $nom;
		$username_to_store = \Drupal::service('pathauto.alias_cleaner')->cleanString($username);
		$sitename = \Drupal::config('system.site')->get('name');

		$ids = \Drupal::entityQuery('user')
			->condition('mail', $email)
			->range(0, 1)
			->execute();

		$usernames = \Drupal::entityQuery('user')
			->condition('name', $username_to_store)
			->range(0, 1)
			->execute();

		if (!empty($ids) || !empty($usernames)) {
			$import_media_service = \Drupal::service('gm_adhesion.helpers_system');
			$sujet = 'Connexion sur ' . $sitename;
			$import_media_service->sendMailSystem($email, $username, $sujet, 'msg_adhesion_meme_email');
			$msg = 'Merci, si tout c\'est bien passé, vous recevrez bientôt un message sur votre boîte email, à bientôt.';
		} else {
			
			// Create user
			$created = \Drupal::time()->getCurrentTime();
			$user = User::create();
			$user->setUsername($username_to_store);
			$user->setPassword($password);
			$user->enforceIsNew();
			$user->setEmail($email);
			$user->set("langcode", $language);
			$user->set("created", strtotime($created));
			$user->set("field_adresse", $field_adresse);
			// $user->set("field_date_de_naissance", $field_date_de_naissance);
			$user->set("field_j_adhere", $field_j_adhere);
			if ($field_genre) {
				$user->set("field_genre", $field_genre);
			}
			if ($field_information_sms === 1) {
				$user->set("field_information_sms", $field_information_sms);
			}
			if ($field_information_par_email === 1) {
				$user->set("field_information_par_email", $field_information_par_email);
			}
			if ($field_je_suis_elu === 1) {
				$user->set("field_je_suis_elu", $field_je_suis_elu);
			}
			$user->set("field_nationalite", $field_nationalite);
			$user->set("field_nom", $nom);
			$user->set("field_prenom", $prenom);
			$user->set("field_telephone_index", $field_telephone_index);
			$user->set("field_telephone", $field_telephone);
			$user->addRole('adherent');
			$user->activate();
			$user->save();
			// Suject of email
			$sujet = 'Votre adhésion ' . $sitename;
			// Send notification
			$import_media_service = \Drupal::service('gm_adhesion.helpers_system');
			$import_media_service->sendMailSystem($email, $username, $sujet, 'msg_nouvelle_adhesion');
			$import_media_service->sendMailNotification($email, $username, $sujet, 'msg_nouvelle_adhesion');
			$msg = 'Merci, si tout c\'est bien passé, vous recevrez bientôt un message sur votre boîte email, à bientôt.';
		}

		$messenger = \Drupal::messenger();
		$messenger->addMessage($msg);
  }

}