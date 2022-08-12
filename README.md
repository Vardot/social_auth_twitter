CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * How it works
 * Support requests
 * Maintainers


INTRODUCTION
------------

Social Auth Twitter Module is a Twitter authentication integration for Drupal.
It is based on the Social Auth and Social API projects

It adds to the site:

 * A new url: `/user/login/twitter`.

 * A settings form at `/admin/config/social-api/social-auth/twitter`.

 * A Twitter logo in the Social Auth Login block.


REQUIREMENTS
------------

This module requires the following modules:

 * [Social Auth](https://drupal.org/project/social_auth)
 * [Social API](https://drupal.org/project/social_api)


INSTALLATION
------------

Install as you would normally install a contributed Drupal module. See
[Installing Modules](https://www.drupal.org/docs/extending-drupal/installing-modules)
for more details.


CONFIGURATION
-------------

In Drupal:

 1. Log in as an admin.

 2. Navigate to Configuration » User authentication » Twitter and
    copy the Callback URL field value (the URL should end in
    `/user/login/twitter/callback`).

In [Twitter Developer Portal](https://developer.twitter.com/portal):

 3. Log in to a Twitter account.

 4. Navigate to Projects & Apps » Overview.

 5. Under Standalone Apps click Create App.

 6. Enter an app name and click Next.

 7. Copy the API Key, API Key Secret, and Bearer Token and save them
    somewhere safe.

 8. Click App settings.

 9. Under User authentication settings click Set up.

 10. Toggle "OAuth 1.0a" (to enable it).

 11. Optionally toggle "Request email from users" (to enable it).

 12. Paste the URL copied from Step 2 in the Callback URI / Redirect URL field.

 13. Fill in other required and optional fields as desired.

 14. Click Save.

In Drupal:

 15. Return to Configuration » User authentication » Twitter

 16. Enter the API Key in the Client ID field.

 17. Enter the API Key Secret in the Client secret field.

 18. Click Save configuration.

 19. Navigate to Structure » Block Layout and place a Social Auth login block
     somewhere on the site (if not already placed).

That's it! Edit the app to elevated to the Production environment after the
implementation has been tested.


HOW IT WORKS
------------

The user can click on the Twitter logo on the Social Auth Login block
You can also add a button or link anywhere on the site that points
to `/user/login/twitter`, so theming and customizing the button or link
is very flexible.

After Twitter has returned the user to your site, the module compares the user
id or email address provided by Twitter. If the user has previously registered
using Twitter or your site already has an account with the same email address,
the user is logged in. If not, a new user account is created. Also, a Twitter
account can be associated with an authenticated user.


SUPPORT REQUESTS
----------------

 * Before posting a support request, carefully read the installation
   instructions provided in module documentation page.

 * Before posting a support request, check the Recent Log entries at
   admin/reports/dblog

 * Once you have done this, you can post a support request at module issue
   queue: [https://www.drupal.org/project/issues/social_auth_twitter](https://www.drupal.org/project/issues/social_auth_twitter)

 * When posting a support request, please inform if you were able to see any
   errors in the Recent Log entries.


MAINTAINERS
-----------

Current maintainers:

 * [Christopher C. Wells (wells)](https://www.drupal.org/u/wells)

Development sponsored by:

 * [Cascade Public Media](https://www.drupal.org/cascade-public-media)
