<?php

namespace Drupal\restrict_role_login_by_ip\EventSubscriber;

use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event Subscriber RestrictRoleLoginByIpEventSubscriber.
 */
class RestrictRoleLoginByIpEventSubscriber implements EventSubscriberInterface {

  /**
   * Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs the event subscriber object.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * Set message when user is denied access.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The kernel request event.
   */
  public function onKernelRequest(RequestEvent $event) {
    $request = $event->getRequest();
    $queryLogout = $request->query->get('logout');
    if (!empty($queryLogout) && $queryLogout == 'restrict') {
      $this->messenger->addMessage(t('You are not allowed to login from this IP address. Please contact the Site Administrator.'), 'error', TRUE);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => 'onKernelRequest',
    ];
  }

}
