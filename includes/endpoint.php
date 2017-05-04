<?php

namespace WSU\RestEmailProxy\Endpoint;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'rest_api_init', 'WSU\RestEmailProxy\Endpoint\register_routes' );

/**
 * Registers the email-proxy/v1/email REST API endpoint.
 *
 * @since 0.0.1
 */
function register_routes() {
	register_rest_route( 'email-proxy/v1', '/email', array(
		'methods' => 'POST',
		'callback' => 'WSU\RestEmailProxy\Endpoint\callback',
	) );
}

/**
 * Handles the email-proxy/v1/email endpoint callback and sends
 * an email when valid information is provided.
 *
 * @since 0.0.1
 *
 * @param \WP_REST_Request $data
 * @return \WP_REST_Response
 */
function callback( $data ) {
	$email = $data->get_json_params();

	// Do not send email by default. Another plugin should control the secret to success.
	if ( ! isset( $email['secret_key'] ) || ! apply_filters( 'rest_email_proxy_valid_secret', false, $email ) ) {
		return new \WP_Rest_Response( array(
			'error' => 'Invalid secret.',
		), 403 );
	}

	$expected = array(
		'send_to',
		'reply_to',
		'send_from_name',
		'subject',
		'message',
		'secret_key',
	);
	foreach ( $expected as $expect ) {
		if ( ! isset( $email[ $expect ] ) ) {
			return new \WP_REST_Response( array(
				'error' => 'An required parameter was not provided in the request.',
			), 400 );
		}
	}

	$send_to = sanitize_email( $email['send_to'] );
	$subject = sanitize_text_field( $email['subject'] );
	$message = wp_kses_post( $email['message'] );

	// Allow for a blanket "from" address that matches the server.
	$from_email = apply_filters( 'rest_email_proxy_default_email', $email['reply_to'] );

	$headers = array(
		'from: "' . sanitize_text_field( $email['send_from_name'] ) . '" <' . sanitize_email( $from_email ) . '>',
		'reply-to: ' . sanitize_email( $email['reply_to'] ),
	);

	$result = wp_mail( $send_to, $subject, $message, $headers );

	if ( $result ) {
		$success = 'Message sent successfully.';
	} else {
		$success = 'An error occurred when sending the email.';
	}

	return new \WP_REST_Response( array(
		'success' => $success,
	), 200 );
}
