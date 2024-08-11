# SilverStripe Google SSO Module

This SilverStripe module enables Google Single Sign-On (SSO) for your SilverStripe application. With this module, you can define a list of authorized email addresses and allow users to log in using their Google accounts.

## Features
- Login using Google OAuth 2.0
- Restrict login to specific email addresses
- Automatically create and log in admin users
- Alternative (but basic) login UI

## Installation

To install this module, you need to add it to your SilverStripe project. You can do this by downloading the module and placing it in the `app` directory of your SilverStripe project.

Alternatively, you can install it via composer:

```bash
composer require larsvanteeffelen/silverstripe-google-sso
```

## Configuration

### Google OAuth 2.0 Setup

To use Google SSO, you need to create a Google OAuth 2.0 client ID and secret. Follow these steps:

1. Go to the [Google Developer Console](https://console.developers.google.com/).
2. Create a new project or select an existing one.
3. Navigate to the "Credentials" page.
4. Create a new OAuth 2.0 Client ID.
5. Set the redirect URI to the following:
   ```
   https://your-domain.com/google-login/callback
   ```
   Replace `your-domain.com` with your actual domain.
6. After creating the client ID, you will get a `Client ID` and a `Client Secret`.

### Environment Variables

Add the following environment variables to your `.env` file in the SilverStripe project:

```
GOOGLE_CLIENT_ID="your-google-client-id"
GOOGLE_CLIENT_SECRET="your-google-client-secret"
ALLOWED_EMAILS="email1@example.com,email2@example.com"
```

- Replace `your-google-client-id` and `your-google-client-secret` with the values obtained from the Google Developer Console.
- Replace `email1@example.com,email2@example.com` with the comma-separated list of authorized email addresses.

### URL Routing

This module registers the following routes:

- `/google-login/login`: Starts the Google OAuth 2.0 login process.
- `/google-login/callback`: Handles the OAuth 2.0 callback from Google and logs in the user if authorized.

These routes can be used if you want to create your own template. However, You can also just use the modified template included with this package.

### Usage

1. Visit the `/admin` URL and click the 'Sign in with Google' button
2. If the user is authorized (i.e., their email is in the `ALLOWED_EMAILS` list), they will be logged in and redirected to the SilverStripe admin panel.
3. If the user is not authorized, they will see an error message.

## License

This module is licensed under the MIT License.
