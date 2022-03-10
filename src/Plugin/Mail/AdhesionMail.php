<?php
namespace Drupal\gm_adhesion\Plugin\Mail;

/**
 * @file
 * Contains \Drupal\gm_adhesion\Plugin\Mail\AdhesionMail.
 */
use Drupal\Core\Mail\MailInterface;
use Drupal\Core\Mail\Plugin\Mail\PhpMail;
use Drupal\Core\Render\Markup;
use Drupal\Core\Site\Settings;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines the plugin Mail.
 *
 * @Mail(
 *   id = "gm_adhesion_mail",
 *   label = @Translation("Adhesion HTML mailer"),
 *   description = @Translation("Sends an HTML email")
 * )
 */
class AdhesionMail extends PHPMail implements MailInterface, ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Render\Renderer;
   */
  protected $renderer;

  /**
   * AdhesionMail constructor.
   *
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The service renderer.
   */
  function __construct(Renderer $renderer) {
    parent::__construct();
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function format(array $message) {
    
    // $message = $this->cleanBody($message);
    $message['options']['texte'] = $message['body'];

    $render = [
      '#theme' => 'adhesion_courriel',
      '#message' => $message,
    ];
    $message['body'] = $this->renderer->renderRoot($render);
    return $message;
  }

  /**
   * {@inheritdoc}
   */
  public function cleanBody($message) {
    return strip_tags($message);
  }

  /**
   * {@inheritdoc}
   */
  public function mail(array $message) {
    return parent::mail($message);
  }

}