<?php

/**
 * @file
 * Contains \Drupal\anonymous_redirect\EventSubscriber\AnonymousRedirectSubscriber.
 *
 * This file defines an event subscriber that redirects anonymous users to a specific page
 * unless they are on certain routes or nodes.
 */

namespace Drupal\anonymous_redirect\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Url;

/**
 * Class AnonymousRedirectSubscriber.
 *
 * Event subscriber to redirect anonymous users to a specific page.
 */
class AnonymousRedirectSubscriber implements EventSubscriberInterface {

  /**
   * The page cache kill switch service.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $killSwitch;

  /**
   * Constructs a new AnonymousRedirectSubscriber.
   *
   * @param \Drupal\Core\PageCache\ResponsePolicy\KillSwitch $kill_switch
   *   The page cache kill switch service.
   */
  public function __construct(KillSwitch $kill_switch) {
    $this->killSwitch = $kill_switch;
  }

  /**
   * Redirects anonymous users to a specific page.
   *
   * This method checks if the current user is anonymous and if they are not on certain
   * routes or nodes. If these conditions are met, the user is redirected to a specific page.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The event to process.
   */
  public function redirectAnonymousUsers(RequestEvent $event): void {
    global $base_url;

    if (
      \Drupal::currentUser()->isAnonymous() &&
      \Drupal::routeMatch()->getRouteName() != 'user.login' &&
      \Drupal::routeMatch()->getRouteName() != 'user.reset.login' &&
      \Drupal::routeMatch()->getRouteName() != 'user.reset' &&
      \Drupal::routeMatch()->getRouteName() != 'user.reset.form' &&
      \Drupal::routeMatch()->getRouteName() != 'user.pass' &&
      \Drupal::routeMatch()->getRouteName() != 'user.register'
    ) {
      $route_name = \Drupal::routeMatch()->getRouteName();
      if (strpos($route_name, 'view') === 0 && strpos($route_name, 'rest_') !== FALSE) {
        return;
      }

      // Disable the page cache for this request
      $this->killSwitch->trigger();

      $response = new RedirectResponse(Url::fromRoute('user.login')->toString(), 302);
      $response->send();
      return;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[KernelEvents::REQUEST][] = ['redirectAnonymousUsers'];
    return $events;
  }

}