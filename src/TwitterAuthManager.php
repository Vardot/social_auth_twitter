<?php

namespace Drupal\social_auth_twitter;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Utility\Error;
use Drupal\social_auth\AuthManager\OAuth2Manager;
use Drupal\social_auth\User\SocialAuthUser;
use Drupal\social_auth\User\SocialAuthUserInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Contains all the logic for Twitter OAuth2 authentication.
 */
class TwitterAuthManager extends OAuth2Manager {

  /**
   * TwitterAuthManager constructor.
   */
  public function __construct(
    ConfigFactory $configFactory,
    LoggerChannelFactoryInterface $logger_factory,
    RequestStack $request_stack,
  ) {
    parent::__construct(
      $configFactory->get('social_auth_twitter.settings'),
      $logger_factory,
      $request_stack->getCurrentRequest(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(): void {
    $code_verifier = $this->request->getSession()->get('code_verifier');
    if ($code = $this->request->query->get('code')) {
      try {
        $this->setAccessToken($this->client->getAccessToken('authorization_code', [
          'code' => $code,
          'code_verifier' => $code_verifier,
        ]));
      }
      catch (\Throwable $e) {
        $this->loggerFactory->get('social_auth_twitter')
          ->error('There was an error during authentication. ' . Error::DEFAULT_ERROR_MESSAGE . ' @backtrace_string', Error::decodeException($e));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getUserInfo(): SocialAuthUserInterface {
    if (!$this->user && $access_token = $this->getAccessToken()) {
      /** @var \Smolblog\OAuth2\Client\Provider\TwitterUser $owner */
      $owner = $this->client->getResourceOwner($access_token);
      $this->user = new SocialAuthUser(
        $owner->getName(),
        $owner->getId(),
        $this->getAccessToken(),
        $owner->getEmail(),
        $owner->getImageUrl(),
        $this->getExtraDetails()
      );
      $this->user->setFirstName($owner->getName());
    }
    return $this->user;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthorizationUrl(): string {
    // Default scopes required for user authentication.
    // - tweet.read: Required by X API for basic access.
    // - users.read: Get user profile information.
    // - users.email: Get user email address (requires app permission).
    // - offline.access: Enable refresh tokens for long-term access.
    $scopes = ['tweet.read', 'users.read', 'users.email', 'offline.access'];

    $extra_scopes = $this->getScopes();
    if ($extra_scopes) {
      $scopes = array_merge($scopes, explode(',', $extra_scopes));
    }

    // Store code verifier to match it later (PKCE support for X/Twitter).
    $this->request->getSession()->set('code_verifier', $this->client->getPkceVerifier());

    // Returns the URL where user will be redirected.
    return $this->client->getAuthorizationUrl([
      'scope' => $scopes,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function requestEndPoint(string $method, string $path, ?string $domain = NULL, array $options = []): mixed {
    if (!$domain) {
      // Use api.twitter.com for consistency with the smolblog/oauth2-twitter
      // library. Both api.twitter.com and api.x.com currently work.
      $domain = 'https://api.twitter.com';
    }

    $url = $domain . $path;

    $request = $this->client->getAuthenticatedRequest($method, $url, $this->getAccessToken(), $options);

    try {
      return $this->client->getParsedResponse($request);
    }
    catch (IdentityProviderException $e) {
      $this->loggerFactory->get('social_auth_twitter')
        ->error('There was an error when requesting ' . $url . '. Exception: ' . $e->getMessage());
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getState(): string {
    return $this->client->getState();
  }

}
