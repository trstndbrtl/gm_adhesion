<?php

namespace Drupal\gm_adhesion;

use Drupal\Core\State\StateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\pathauto\AliasCleanerInterface;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\UserInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\media\MediaInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Url;

/**
 * Provides the default database storage backend for statistics.
 * 
 * @package Drupal\baarr_app
 */
class MgSystem implements MgSystemInterface {

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The alias cleaner.
   *
   * @var \Drupal\pathauto\AliasCleanerInterface
   */
  protected $aliasCleaner;

  /**
   * The alias manager that caches alias lookups based on the request.
   *
   * @var \Drupal\path_alias\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * Constructs the statistics storage.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   * @param \Drupal\pathauto\AliasCleanerInterface $alias_cleaner
   *   The alias cleaner.
   * @param \Drupal\path_alias\AliasManagerInterface $alias_manager
   *   The alias cleaner.
   */
  public function __construct(StateInterface $state, EntityTypeManagerInterface $entity_type_manager, LanguageManagerInterface $language_manager, AccountProxyInterface $current_user, AliasCleanerInterface $alias_cleaner, AliasManagerInterface $alias_manager) {
    $this->state = $state;
    $this->entityTypeManager = $entity_type_manager;
    $this->languageManager = $language_manager;
    $this->currentUser = $current_user;
    $this->aliasCleaner = $alias_cleaner;
    $this->aliasManager = $alias_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function sendMailSystem($email, $user_name, $sujet, $type = NULL) {

    $config = \Drupal::config('gm_adhesion.settings');
    
    $msg = '';
    $title = '';
    if ($type && is_string($msg)) {
      if ($type == 'msg_adhesion_meme_email') {
        $title = ($config->get('mailer_sys.msg_adhesion_meme_email_title') !== NULL) ? $config->get('mailer_sys.msg_adhesion_meme_email_title') : NULL;
        $msg = $config->get('mailer_sys.msg_adhesion_meme_email')['value'];
      }elseif ($type == 'msg_stop_adhesion') {
        $title = ($config->get('mailer_sys.msg_stop_adhesion_title') !== NULL) ? $config->get('mailer_sys.msg_stop_adhesion_title') : NULL;
        $msg = $config->get('mailer_sys.msg_stop_adhesion')['value'];
      }elseif ($type == 'msg_nouveau_don') {
        $title = ($config->get('mailer_sys.msg_nouveau_don_title') !== NULL) ? $config->get('mailer_sys.msg_nouveau_don_title') : NULL;
        $msg = $config->get('mailer_sys.msg_nouveau_don')['value'];
      }elseif ($type == 'msg_inscription_news') {
        $title = ($config->get('mailer_sys.msg_inscription_news_title') !== NULL) ? $config->get('mailer_sys.msg_inscription_news_title') : NULL;
        $msg = $config->get('mailer_sys.msg_inscription_news')['value'];
      }elseif ($type == 'msg_stop_news') {
        $title = ($config->get('mailer_sys.msg_stop_news_title') !== NULL) ? $config->get('mailer_sys.msg_stop_news_title') : NULL;
        $msg = $config->get('mailer_sys.msg_stop_news')['value'];
      }elseif ($type == 'msg_nouvelle_adhesion') {
        $title = ($config->get('mailer_sys.msg_nouvelle_adhesion_title') !== NULL) ? $config->get('mailer_sys.msg_nouvelle_adhesion_title') : NULL;
        $msg = $config->get('mailer_sys.msg_nouvelle_adhesion')['value'];
      }
    }

    $logo = ($config->get('mailer_sys.logo_header') !== NULL) ? $config->get('mailer_sys.logo_header') : NULL;
    $email_admin = ($config->get('mailer_sys.email_admin') !== NULL) ? $config->get('mailer_sys.email_admin') : NULL;

    $site_system = \Drupal::config('system.site');
    $site_name = $site_system->get('name');
    
    if ($email_admin && $email && $user_name && $msg != '' && is_string($msg)) {
      $send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail(); // this is used to send HTML emails
      $from = $site_system->get('mail');
      $to = $email;
      $message['headers'] = array(
        'content-type' => 'text/html',
        'MIME-Version' => '1.0',
        'reply-to' => $from,
        'from' => $site_name. ' <'.$from.'>'
      );
      $message['to'] = $to;
      $message['subject'] = $sujet;

      $theme_body = array(
        '#theme' => 'adhesion_courriel',
        '#notification' => t($msg),
        '#title' => t($title),
        '#username' => $user_name,
        '#site_name' => $site_name,
        '#logo' => $logo,
      );

      // Send the email
      $mail_body = \Drupal::service('renderer')->renderPlain($theme_body);
      $message['body'] = $mail_body;

      $send_mail->mail($message);
    }
    
  }

    /**
   * {@inheritdoc}
   */
  public function sendMailNotification($email, $user_name, $sujet, $type = NULL) {

    $config = \Drupal::config('gm_adhesion.settings');

    $msg = '';
    $title = '';
    if ($type && is_string($msg)) {
      if ($type == 'msg_stop_adhesion') {
        $sujet = 'Stop adhésion';
        $title = 'Un utilisateur à stopper son adhésion';
        $msg .= '<p>L\'utilisateur '. $user_name .' avec l\'email '.$email.' à stopper son adhésion</p>';
      }elseif ($type == 'msg_nouveau_don') {
        $sujet = 'Nouveau don';
        $title = 'Un utilisateur à fait un don';
        $msg .= '<h3>Un nouveau don a été fait.</h3>';
        $msg .= '<p>l\'utilisateur '. $user_name .' avec l\'email '.$email.' à fait un nouveau don.</p>';
      }elseif ($type == 'msg_inscription_news') {
        $sujet = 'Nouvelle souscription newsletter';
        $title = 'Un utilisateur s\'inscrit à la newsletter';
        $msg .= '<h3>Une nouvelle subscritption à la newsletter.</h3>';
        $msg .= '<p>l\'utilisateur '. $user_name .' avec l\'email '.$email.' s\'est inscrit à la newsletter.</p>';
      }elseif ($type == 'msg_stop_news') {
        $sujet = 'Stop newsletter';
        $title = 'Un utilisateur à stopper son inscription à la newsletters';
        $msg .= '<p>l\'utilisateur '. $user_name .' avec l\'email '.$email.'  à stoppé sa souscription à la newsletter.</p>';
      }elseif ($type == 'msg_nouvelle_adhesion') {
        $sujet = 'Nouvelle adhésion';
        $title = 'Nous avons un nouvel adhérent';
        $msg .= '<h3>Un nouveau adhérent.</h3>';
        $msg .= '<p>l\'utilisateur '. $user_name .' avec l\'email '.$email.' vient d\'adhérer.</p>';
        $msg .= '<p>Pour en savoir plus, rdv sur la page des utilisateurs.</p>';
      }
    }

    $logo = ($config->get('mailer_sys.logo_header') !== NULL) ? $config->get('mailer_sys.logo_header') : NULL;
    $email_admin_receive_notifications = ($config->get('mailer_sys.email_admin') !== NULL) ? $config->get('mailer_sys.email_admin') : NULL;


    $site_system = \Drupal::config('system.site');
    $site_name = $site_system->get('name');
    $site_email = $site_system->get('mail');

    $send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail(); // this is used to send HTML emails
    $from = 'mail@gentilmechant.com';
    $to = $email_admin_receive_notifications;
    $message['headers'] = array(
      'content-type' => 'text/html',
      'MIME-Version' => '1.0',
      'reply-to' => $site_email,
      'from' => $site_name. ' <'.$site_email.'>'
    );
    $message['to'] = $to;
    $message['subject'] = $sujet;

    $theme_body = array(
      '#theme' => 'admin_notifications',
      '#notification' => t($msg),
      '#title' => t($title),
      '#username' => $user_name,
      '#site_name' => $site_name,
      '#logo' => $logo,
    );

    $mail_body = \Drupal::service('renderer')->renderPlain($theme_body);
    $message['body'] = $mail_body;

    $send_mail->mail($message);

  }

  /**
   * {@inheritdoc}
   */
  public function getCountryOptions() {
    $country = array(
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
    );
    return $country;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrefixCodeOptions() {
   $code = array(
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
    );

    return $code;

  }
  
}
