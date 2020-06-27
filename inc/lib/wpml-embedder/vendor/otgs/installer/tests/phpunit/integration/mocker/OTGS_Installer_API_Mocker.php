<?php

class OTGS_Installer_API_Mocker {

	public function mockEndpointResponse( OTGS_Installer_Endpoint_Mock $endpoint_mock ) {
		if ( $endpoint_mock->getMethod() === 'GET' ) {
			$this->mock_get( $endpoint_mock );
		}

		if ( $endpoint_mock->getMethod() === 'POST' ) {
			$this->mock_post( $endpoint_mock );
		}

		\WP_Mock::userFunction(
			'is_wp_error',
			[
				'args' => [$endpoint_mock->getResponse()],
				'return' => $endpoint_mock->isWpError(),
			]
		);

		\WP_Mock::userFunction(
			'wp_remote_retrieve_body',
			[
				'args' => [$endpoint_mock->getResponse()],
				'return' => $endpoint_mock->getResponseBody(),
			]
		);
	}

	private function mock_get( OTGS_Installer_Endpoint_Mock $endpoint_mock ) {
		\WP_Mock::userFunction(
			'wp_remote_get',
			[
				'args' => [$endpoint_mock->getEndpointUrl()],
				'return' => $endpoint_mock->getResponse(),
			]
		);
	}

	private function mock_post( OTGS_Installer_Endpoint_Mock $endpoint_mock ) {
		\WP_Mock::userFunction(
			'wp_remote_post',
			[
				'args' => [$endpoint_mock->getEndpointUrl(), $endpoint_mock->getRequest()],
				'return' => $endpoint_mock->getResponse(),
			]
		);
	}
}