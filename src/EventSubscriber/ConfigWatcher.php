<?php

namespace Drupal\wwm_devel\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Drupal\Core\StringTranslation\StringTranslationTrait;
use \Drupal\Core\Session\AccountInterface;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Class ConfigWatcher.
 */
class ConfigWatcher implements EventSubscriberInterface {

  use StringTranslationTrait;


  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * ConfigWatcher constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The account for which view access should be checked.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(AccountInterface $current_user, MessengerInterface $messenger) {
    $this->currentUser = $current_user;
    $this->messenger = $messenger;
  }

  /**
   * Let privileged user known when a config is saved.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   The configuration event.
   */
  public function onConfigSave(ConfigCrudEvent $event) {
    if ($this->currentUser->hasPermission('wwm_devel know config saved')) {
      $saved_config = $event->getConfig();
      $this->messenger->addStatus($this->t('Config object "@name" has been saved.', ['@name' => $saved_config->getName()]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ConfigEvents::SAVE][] = ['onConfigSave', 0];
    return $events;
  }

}
