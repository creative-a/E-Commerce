<?php

class OTGS_Installer_Endpoint_Mock {
	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var string
	 */
	private $endpoint_url;

	/**
	 * @var array|mixed
	 */
	private $response;

	/**
	 * @var array|mixed
	 */
	private $request;

	/**
	 * @var bool
	 */
	private $isWpError;

	/**
	 * @param string $method
	 * @param string $endpoint
	 * @param string $response
	 * @param bool $isWpError
	 */
	public function __construct( $method, $endpoint, $request, $response, $isWpError ) {
		$this->method       = $method;
		$this->endpoint_url = $endpoint;
		$this->request     = $request;
		$this->response     = $response;
		$this->isWpError    = $isWpError;
	}

	public static function createGetMock( $endpoint, $response, $isWpError = false ) {
		return new OTGS_Installer_Endpoint_Mock(
			'GET',
			$endpoint,
			[],
			$response,
			$isWpError
		);
	}

	public static function createPostMock( $endpoint, $request, $response, $isWpError = false ) {
		return new OTGS_Installer_Endpoint_Mock(
			'POST',
			$endpoint,
			$request,
			$response,
			$isWpError
		);
	}

	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * @return string
	 */
	public function getEndpointUrl() {
		return $this->endpoint_url;
	}

	/**
	 * @return string
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @return array
	 */
	public function getRequest() {
		return ['body' => $this->request];
	}

	/**
	 * @return string
	 */
	public function getResponseBody() {
		return $this->get_response_from_file($this->response['body']);
	}

	private function get_response_from_file($file_name) {
		return file_get_contents( $this->get_responses_file_path( $file_name ) );
	}

	/**
	 * @return string
	 */
	private function get_responses_file_path( $file_name ) {
		return TEST_ROOT_DIR . '/data/responses/' . $file_name;
	}
	/**
	 * @return bool
	 */
	public function isWpError() {
		return $this->isWpError;
	}
}