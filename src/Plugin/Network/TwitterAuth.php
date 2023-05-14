<?php

namespace Drupal\social_auth_twitter\Plugin\Network;

use Drupal\social_auth\Plugin\Network\NetworkBase;
use Drupal\social_auth\Plugin\Network\NetworkInterface;

/**
 * Defines Social Auth Twitter Network Plugin.
 *
 * @Network(
 *   id = "social_auth_twitter",
 *   short_name = "twitter",
 *   social_network = "Twitter",
 *   img_path = "img/twitter_logo.svg",
 *   type = "social_auth",
 *   class_name = "\Abraham\TwitterOAuth\TwitterOAuth",
 *   auth_manager = "\Drupal\social_auth_twitter\TwitterAuthManager",
 *   routes = {
 *     "redirect": "social_auth.network.redirect",
 *     "callback": "social_auth.network.callback",
 *     "settings_form": "social_auth.network.settings_form",
 *   },
 *   handlers = {
 *     "settings": {
 *       "class": "\Drupal\social_auth\Settings\SettingsBase",
 *       "config_id": "social_auth_twitter.settings"
 *     }
 *   }
 * )
 */
class TwitterAuth extends NetworkBase implements NetworkInterface {}
