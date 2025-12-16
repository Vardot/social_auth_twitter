<?php

namespace Drupal\social_auth_twitter\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\social_auth\Form\SocialAuthSettingsForm;
use Drupal\social_auth\Plugin\Network\NetworkInterface;

/**
 * Settings form for Social Auth Twitter.
 */
class TwitterAuthSettingsForm extends SocialAuthSettingsForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'social_auth_twitter_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return array_merge(
      parent::getEditableConfigNames(),
      ['social_auth_twitter.settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?NetworkInterface $network = NULL): array {
    /** @var \Drupal\social_auth\Plugin\Network\NetworkInterface $network */
    $network = $this->networkManager->createInstance('social_auth_twitter');
    $form = parent::buildForm($form, $form_state, $network);

    $form['network']['advanced']['scopes']['#description'] =
      $this->t('Define any additional scopes to be requested, separated by a comma.<br>
        The scopes \'tweet.read\', \'users.read\', \'users.email\', and \'offline.access\' are added by default and always requested.<br>
        Note: To retrieve user email, you must enable "Request email from users" in your X Developer App settings.<br>
        You can see the full list of valid scopes and their description <a href="@scopes">here</a>.', [
          '@scopes' => 'https://docs.x.com/resources/fundamentals/authentication/oauth-2-0/authorization-code#scopes',
        ]);

    $form['network']['advanced']['endpoints']['#description'] =
       $this->t('Define the Endpoints to be requested when user authenticates with X for the first time<br>
        Enter each endpoint in different lines in the format <em>endpoint</em>|<em>name_of_endpoint</em>.'
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $values = $form_state->getValues();
    $this->config('social_auth_twitter.settings')
      ->set('client_id', trim($values['client_id']))
      ->set('client_secret', trim($values['client_secret']))
      ->set('scopes', $values['scopes'])
      ->set('endpoints', $values['endpoints'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
