<?php

/**
 * Class Test_OTGS_Installer_Support_Template
 *
 * @group installer-508
 */
class Test_OTGS_Installer_Support_Template extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_renders_template() {
		$template_service = $this->getMockBuilder( 'OTGS_Php_Template_Service' )
		                         ->setMethods( array( 'show' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$logger_storage = $this->getMockBuilder( 'OTGS_Installer_Logger_Storage' )
		                       ->setMethods( array( 'get' ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$requirements = $this->getMockBuilder( 'OTGS_Installer_Requirements' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$installer_instances = $this->getMockBuilder( 'OTGS_Installer_Instances' )
		                            ->disableOriginalConstructor()
		                            ->getMock();
		$instance = new OTGS_Installer_Instance();
		$instance->set_delegated( false )
		         ->set_version( '1.0.0' )
		         ->set_high_priority( false )
		         ->set_bootfile( 'path/to/installer' );

		$instances = array( $instance );

		$request_url       = 'api.wpml.org';
		$request_arguments = '?test=123';
		$response          = 'service unavailable';
		$component         = 'subscription';
		$time              = date( 'Y-m-d h:m:s' );

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
		            ->setMethods( array(
			            'get_request_url',
			            'get_request_args',
			            'get_response',
			            'get_component',
			            'get_time'
		            ) )
		            ->disableOriginalConstructor()
		            ->getMock();

		$log->method( 'get_request_url' )
		    ->willReturn( $request_url );

		$log->method( 'get_request_args' )
		    ->willReturn( $request_arguments );

		$log->method( 'get_response' )
		    ->willReturn( $response );

		$log->method( 'get_component' )
		    ->willReturn( $component );

		$log->method( 'get_time' )
		    ->willReturn( $time );

		$logger_storage->method( 'get' )
		               ->willReturn( array( $log ) );

		$nonce = 'nonce_tag';

		\WP_Mock::userFunction( 'wp_nonce_field', array(
			'args'   => array(
				OTGS_Installer_Connection_Test_Ajax::ACTION,
				OTGS_Installer_Connection_Test_Ajax::ACTION,
				false
			),
			'return' => $nonce,
		) );

		$items = array();

		$requirements->method( 'get' )
		             ->willReturn( $items );

		$installer_instances->method( 'get' )
		                    ->willReturn( $instances );

		$model = array(
			'log_entries'  => array(
				array(
					'request_url'       => $request_url,
					'request_arguments' => $request_arguments,
					'response'          => $response,
					'component'         => $component,
					'time'              => $time
				),
			),
			'strings'      => array(
				'page_title'   => 'Installer Support',
				'log'          => array(
					'title'             => 'Installer Log',
					'request_url'       => 'Request URL',
					'request_arguments' => 'Request Arguments',
					'response'          => 'Response',
					'component'         => 'Component',
					'time'              => 'Time',
					'empty_log'         => 'Log is empty',
				),
				'tester'       => array(
					'title'        => 'Installer System Status',
					'button_label' => 'Check Now',
				),
				'requirements' => array(
					'title' => 'Required PHP Libraries',
				),
				'instances'    => array(
					'title'         => 'All Installer Instances',
					'path'          => 'Path',
					'version'       => 'Version',
					'high_priority' => 'High priority',
					'delegated'     => 'Delegated',
				),
			),
			'tester'       => array(
				'endpoints' => array(
					array( 'repository' => 'wpml', 'type' => 'api', 'description' => 'WPML API server' ),
					array( 'repository' => 'toolset', 'type' => 'api', 'description' => 'Toolset API server' )
				),
				'nonce'     => $nonce,
			),
			'requirements' => $items,
			'instances'    => $instances,
		);

		$template_service->expects( $this->once() )
		                 ->method( 'show' )
		                 ->with( $model, OTGS_Installer_Support_Template::TEMPLATE_FILE );

		$subject = new OTGS_Installer_Support_Template( $template_service, $logger_storage, $requirements, $installer_instances );
		$subject->show();
	}

	/**
	 * @test
	 * @group wpmlcore-5837
	 */
	public function it_renders_support_link() {
		$template_service = $this->getMockBuilder( 'OTGS_Php_Template_Service' )
		                         ->setMethods( array( 'show' ) )
		                         ->disableOriginalConstructor()
		                         ->getMock();

		$logger_storage = $this->getMockBuilder( 'OTGS_Installer_Logger_Storage' )
		                       ->setMethods( array( 'get' ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$requirements = $this->getMockBuilder( 'OTGS_Installer_Requirements' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$installer_instances = $this->getMockBuilder( 'OTGS_Installer_Instances' )
		                            ->disableOriginalConstructor()
		                            ->getMock();

		$instance = new OTGS_Installer_Instance();
		$instance->set_delegated( false )
		         ->set_version( '1.0.0' )
		         ->set_high_priority( false )
		         ->set_bootfile( 'path/to/installer' );

		$instances = array( $instance );

		$items = array();

		$requirements->method( 'get' )
		             ->willReturn( $items );

		$installer_instances->method( 'get' )
		                    ->willReturn( $instances );

		$link = 'installer/support/page/link';

		\WP_Mock::userFunction( 'admin_url', array(
			'args' => 'admin.php?page=otgs-installer-support',
			'return' => $link,
		) );

		$model = array(
			'title' => 'Installer Support',
			'content' => 'For retrieving Installer debug information use the %s page.',
			'link' => array(
				'url' => $link,
				'text' => 'Installer Support',
			),
			'hide_title' => false,
		);

		$template_service->expects( $this->once() )
		                 ->method( 'show' )
		                 ->with( $model, OTGS_Installer_Support_Template::SUPPORT_LINK );

		$subject = new OTGS_Installer_Support_Template( $template_service, $logger_storage, $requirements, $installer_instances );
		$subject->render_support_link();
	}
}
