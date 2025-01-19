<?php

namespace Drupal\login_redirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;

/**
 * Class LoginRedirectSubscriber.
 *
 * Event subscriber to redirect users to the front page upon login.
 */
class LoginRedirectSubscriber implements EventSubscriberInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new LoginRedirectSubscriber.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   */
  public function __construct(AccountInterface $current_user, RouteMatchInterface $route_match) {
    $this->currentUser = $current_user;
    $this->routeMatch = $route_match;
  }

  /**
   * Redirects users to the front page upon login.
   *
   * This method checks if the current route is the user login route and if the user is authenticated.
   * If these conditions are met, the user is redirected to the front page.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The event to process.
   */
  public function onKernelResponse(ResponseEvent $event) {
    // Check if the current route is the user login route.
    if ($this->routeMatch->getRouteName() === 'user.login' || $this->routeMatch->getRouteName() === 'user.register') {
      // Check if the user is authenticated.
      if ($this->currentUser->isAuthenticated()) {
        // Redirect to the front page.
        $response = new RedirectResponse(Url::fromRoute('<front>')->toString());
        $event->setResponse($response);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onKernelResponse'];
    return $events;
  }

}