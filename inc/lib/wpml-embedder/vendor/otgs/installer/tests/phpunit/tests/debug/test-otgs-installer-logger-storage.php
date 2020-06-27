<?php

/**
 * Class Test_OTGS_Installer_Logger_Storage
 *
 * @group installer-505
 * @group adriano
 */
class Test_OTGS_Installer_Logger_Storage extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_gets_log_entries() {
		$time    = date( 'Y-m-d h:m:s' );
		$args    = '?test=1&test2=3';
		$url     = 'api.wpml.org';
		$message = 'Connection failed';

		$log_entries = array(
			array(
				'request_args' => $args,
				'request_url'  => $url,
				'response'     => $message,
				'component'    => OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION,
				'time'         => $time,
			)
		);

		$log = $this->getMockBuilder( 'OTGS_Installer_Log' )
		            ->setMethods( array(
			            'set_request_args',
			            'set_request_url',
			            'set_response',
			            'set_time',
			            'set_component'
		            ) )
		            ->disableOriginalConstructor()
		            ->getMock();

		$log->method( 'set_request_args' )
		    ->with( $args )
		    ->willReturn( $log );

		$log->method( 'set_request_url' )
		    ->with( $url )
		    ->willReturn( $log );

		$log->method( 'set_response' )
		    ->with( $message )
		    ->willReturn( $log );

		$log->method( 'set_time' )
		    ->with( $time )
		    ->willReturn( $log );

		$log->method( 'set_component' )
		    ->with( OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION )
		    ->willReturn( $log );

		
		$logger_factory = $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
			->setMethods( array( 'create' ) )
			->disableOriginalConstructor()
			->getMock();

		$logger_factory->method( 'create' )
			->willReturn( $log );

		WP_Mock::wpFunction( 'get_option', array(
			'args'   => OTGS_Installer_Logger_Storage::OPTION_KEY,
			'return' => $log_entries,
		) );

		$subject = new OTGS_Installer_Logger_Storage( $logger_factory );
		$this->assertEquals( array( $log ), $subject->get() );
	}

	/**
	 * @test
	 */
	public function it_adds_new_log_entry() {
		$time    = date( 'Y-m-d h:m:s' );
		$args    = '?test=1&test2=3';
		$url     = 'api.wpml.org';
		$message = 'Connection failed';

		$log_object = $this->getMockBuilder( 'OTGS_Installer_Log' )
		                   ->setMethods( array(
			                   'get_request_args',
			                   'get_request_url',
			                   'get_response',
			                   'get_component',
			                   'get_time'
		                   ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$log_object->method( 'get_request_args' )
		           ->willReturn( $args );

		$log_object->method( 'get_request_url' )
		           ->willReturn( $url );

		$log_object->method( 'get_response' )
		           ->willReturn( $message );

		$log_object->method( 'get_component' )
		           ->willReturn( OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION );

		$log_object->method( 'get_time' )
		           ->willReturn( $time );

		WP_Mock::wpFunction( 'get_option', array(
			'args'   => OTGS_Installer_Logger_Storage::OPTION_KEY,
			'return' => array(),
		) );

		WP_Mock::wpFunction( 'update_option', array(
			'times' => 1,
			'args'  => array(
				OTGS_Installer_Logger_Storage::OPTION_KEY,
				array(
					array(
						'request_args' => $args,
						'request_url'  => $url,
						'response'     => $message,
						'component'    => OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION,
						'time'         => $time
					)
				)
			),
		) );

		$logger_factory = $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
		                       ->setMethods( array( 'create' ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$subject = new OTGS_Installer_Logger_Storage( $logger_factory );
		$subject->add( $log_object );
	}

	/**
	 * @test
	 */
	public function it_should_cut_down_items_to_match_the_limit_when_adding_new_entry() {
		$time              = date( 'Y-m-d h:m:s' );
		$args              = '?test=1&test2=3';
		$url               = 'api.wpml.org';
		$message           = 'Connection failed';
		$message_old_entry = 'Site unavailable';

		$old_log_object = $this->getMockBuilder( 'OTGS_Installer_Log' )
		                       ->setMethods( array(
			                       'get_request_args',
			                       'get_request_url',
			                       'get_response',
			                       'get_component',
			                       'get_time'
		                       ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$old_log_object->method( 'get_request_args' )
		               ->willReturn( $args );

		$old_log_object->method( 'get_request_url' )
		               ->willReturn( $url );

		$old_log_object->method( 'get_response' )
		               ->willReturn( $message_old_entry );

		$old_log_object->method( 'get_component' )
		               ->willReturn( OTGS_Installer_Logger_Storage::COMPONENT_REPOSITORIES );

		$old_log_object->method( 'get_time' )
		               ->willReturn( $time );

		$log_object = $this->getMockBuilder( 'OTGS_Installer_Log' )
		                   ->setMethods( array(
			                   'get_request_args',
			                   'get_request_url',
			                   'get_response',
			                   'get_component',
			                   'get_time'
		                   ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$log_object->method( 'get_request_args' )
		           ->willReturn( $args );

		$log_object->method( 'get_request_url' )
		           ->willReturn( $url );

		$log_object->method( 'get_response' )
		           ->willReturn( $message );

		$log_object->method( 'get_component' )
		           ->willReturn( OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION );

		$log_object->method( 'get_time' )
		           ->willReturn( $time );

		WP_Mock::wpFunction( 'get_option', array(
			'args'   => OTGS_Installer_Logger_Storage::OPTION_KEY,
			'return' => array(
				array(
					'request_args' => $args,
					'request_url'  => $url,
					'response'     => $message_old_entry,
					'time'         => $time,
					'component'    => OTGS_Installer_Logger_Storage::COMPONENT_REPOSITORIES,
				),
			),
		) );

		WP_Mock::wpFunction( 'update_option', array(
			'times' => 1,
			'args'  => array(
				OTGS_Installer_Logger_Storage::OPTION_KEY,
				array(
					array(
						'request_args' => $args,
						'request_url'  => $url,
						'response'     => $message,
						'component'    => OTGS_Installer_Logger_Storage::COMPONENT_SUBSCRIPTION,
						'time'         => $time
					)
				)
			),
		) );

		$logger_factory = $this->getMockBuilder( 'OTGS_Installer_Log_Factory' )
		                       ->setMethods( array( 'create' ) )
		                       ->disableOriginalConstructor()
		                       ->getMock();

		$logger_factory->method( 'create' )
		               ->willReturn( $old_log_object );

		$subject = new OTGS_Installer_Logger_Storage( $logger_factory, 1 );
		$subject->add( $log_object );
	}
}