# WSUWP REST Email Proxy

[![Build Status](https://travis-ci.org/washingtonstateuniversity/WSUWP-Plugin-REST-Email-Proxy.svg?branch=master)](https://travis-ci.org/washingtonstateuniversity/WSUWP-Plugin-REST-Email-Proxy)

A WordPress plugin that provides a REST proxy for sending email from remote WordPress servers.

In a large organization, it's possible that sending email from outside servers can be a painful process. WSUWP REST Email Proxy provides a REST endpoint for sending mail from a server that is already properly configured.

## Endpoint

The endpoint lives at `wp-json/email-proxy/v1/email` and only accepts `POST` requests.

The `POST` request must contain the following data:

```json
{
		"send_to": "detination@email.address",
		"reply_to": "reply_to@email_address",
		"send_from_name": "From Name",
		"subject": "Subject of the email",
		"message": "Contents of the email message",
		"secret_key": "qwerty12345"
}
```

## Secret Key

By default, the plugin will not send an email. Access is managed through the `rest_email_proxy_valid_secret` filter, which defaults to `false`.

This filter also provides the full contents of the `POST` email data so that your code can determine if it's a valid send attempt.

It's suggested that the `secret_key` data is used to control access as this is required in the request.

## Default From Email

The `rest_email_proxy_default_email` filter is provided so that a default from address can be set. If one is not provided, the `reply_to` address is used in its place.

## REST Response

The responses returned by the plugin are very basic.

* If the request does not pass the secret test, a `403` response is returned.
* If the request does not include the required data, a `400` response is returned.
* If `wp_mail()` processes, a `200` response is returned. The boolean `success` message will indicate whether the email sent.
