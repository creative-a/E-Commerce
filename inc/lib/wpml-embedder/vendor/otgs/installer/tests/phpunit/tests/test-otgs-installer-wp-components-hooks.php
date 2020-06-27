<?php

/**
 * Class Test_OTGS_Installer_WP_Components_Hooks
 *
 * @group local-components
 * @group installer-370
 */
class Test_OTGS_Installer_WP_Components_Hooks extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
			->disableOriginalConstructor()
			->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
			->disableOriginalConstructor()
			->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );

		\WP_Mock::expectActionAdded( 'wp_ajax_end_user_get_info', array( $subject, 'process_report_instantly' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_' . OTGS_Installer_WP_Components_Setting_Ajax::AJAX_ACTION, array( $subject, 'force_send_components_data' ), OTGS_Installer_WP_Components_Setting_Ajax::SAVE_SETTING_PRIORITY + 1 );
		\WP_Mock::expectActionAdded( OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY, array( $subject, 'send_components_data' ) );
		\WP_Mock::expectActionAdded( OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_AFTER_REGISTRATION, array( $subject, 'send_components_data' ) );
		\WP_Mock::expectActionAdded( 'init', array( $subject, 'schedule_components_report' ) );
		\WP_Mock::expectActionAdded( 'wp_ajax_save_site_key', array( $subject, 'schedule_components_report_when_product_is_registered' ) );

		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_schedules_components_report() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
			->disableOriginalConstructor()
			->getMock();

		WP_Mock::wpFunction( 'wp_next_scheduled', array(
			'return' => false,
			'args' => OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY,
		));

		WP_Mock::wpFunction( 'wp_schedule_single_event', array(
			'times' => 1,
			'args' => array( strtotime( OTGS_Installer_WP_Components_Hooks::REPORT_SCHEDULING_PERIOD ), OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY ),
		));

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->schedule_components_report();
	}

	/**
	 * @test
	 */
	public function it_does_not_schedule_components_report_because_it_is_scheduled_already() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		WP_Mock::wpFunction( 'wp_next_scheduled', array(
			'return' => true,
			'args' => OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY,
		));

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->schedule_components_report();
	}

	/**
	 * @test
	 * @group installer-431
	 */
	public function it_schedules_components_report_when_saving_site_key() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		WP_Mock::wpFunction( 'wp_next_scheduled', array(
			'return' => false,
			'args' => OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_AFTER_REGISTRATION,
		));

		WP_Mock::wpFunction( 'wp_schedule_single_event', array(
			'times' => 1,
			'args' => array( time() + 60, OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_AFTER_REGISTRATION ),
		));

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->schedule_components_report_when_product_is_registered();
	}

	/**
	 * @test
	 * @group installer-431
	 */
	public function it_does_not_schedule_components_report_when_saving_site_key_because_it_is_scheduled_already() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		WP_Mock::wpFunction( 'wp_next_scheduled', array(
			'return' => true,
			'args' => OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_AFTER_REGISTRATION,
		));

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->schedule_components_report_when_product_is_registered();
	}

	/**
	 * @test
	 */
	public function it_sends_components_data() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
			->setMethods( array( 'is_outdated', 'refresh_cache', 'get' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
			->setMethods( array( 'send' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$storage->method( 'is_outdated' )
			->willReturn( true );

		$storage->expects( $this->once() )
			->method( 'refresh_cache' );

		$components = array( 'something' );

		$storage->method( 'get' )
			->willReturn( $components );

		$sender->expects( $this->once() )
			->method( 'send' )
			->with( $components );

		WP_Mock::wpFunction( 'wp_next_scheduled', array(
			'return' => true,
			'args' => OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY,
		));

		WP_Mock::wpFunction( 'wp_schedule_single_event', array(
			'times' => 0,
			'return' => false,
			'args' => array( strtotime( OTGS_Installer_WP_Components_Hooks::REPORT_SCHEDULING_PERIOD ), OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY ),
		));

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->send_components_data();
	}

	/**
	 * @test
	 */
	public function it_sends_components_data_by_force() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
		                ->setMethods( array( 'refresh_cache', 'get' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
		               ->setMethods( array( 'send' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$storage->expects( $this->once() )
		        ->method( 'refresh_cache' );

		$components = array( 'something' );

		$storage->method( 'get' )
		        ->willReturn( $components );

		$sender->expects( $this->once() )
		       ->method( 'send' )
		       ->with( $components );

		WP_Mock::wpFunction( 'wp_next_scheduled', array(
			'return' => true,
			'args' => OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY,
		));

		WP_Mock::wpFunction( 'wp_schedule_single_event', array(
			'times' => 0,
			'return' => false,
			'args' => array( strtotime( OTGS_Installer_WP_Components_Hooks::REPORT_SCHEDULING_PERIOD ), OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY ),
		));

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->force_send_components_data();
	}

	/**
	 * @test
	 * @group installer-433
	 */
	public function it_sends_components_data_and_request_to_be_processed_instantly() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
		                ->setMethods( array( 'refresh_cache', 'get' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
		               ->setMethods( array( 'send' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$storage->expects( $this->once() )
		        ->method( 'refresh_cache' );

		$components = array( 'something' );

		$storage->method( 'get' )
		        ->willReturn( $components );

		$sender->expects( $this->once() )
		       ->method( 'send' )
		       ->with( $components, true );

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->process_report_instantly();
	}

	/**
	 * @test
	 */
	public function it_does_not_send_components_data_because_it_is_up_to_date() {
		$storage = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Storage' )
		                ->setMethods( array( 'is_outdated', 'refresh_cache', 'get' ) )
		                ->disableOriginalConstructor()
		                ->getMock();

		$sender = $this->getMockBuilder( 'OTGS_Installer_WP_Components_Sender' )
		               ->setMethods( array( 'send' ) )
		               ->disableOriginalConstructor()
		               ->getMock();

		$settings = $this->getMockBuilder( 'OTGS_Installer_WP_Share_Local_Components_Setting' )
		                 ->disableOriginalConstructor()
		                 ->getMock();

		$storage->method( 'is_outdated' )
		        ->willReturn( false );

		$storage->expects( $this->never() )
		        ->method( 'refresh_cache' );

		$sender->expects( $this->never() )
		       ->method( 'send' );

		WP_Mock::wpFunction( 'wp_next_scheduled', array(
			'return' => true,
			'args' => OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY,
		));

		WP_Mock::wpFunction( 'wp_schedule_single_event', array(
			'times' => 0,
			'return' => false,
			'args' => array( strtotime( OTGS_Installer_WP_Components_Hooks::REPORT_SCHEDULING_PERIOD ), OTGS_Installer_WP_Components_Hooks::EVENT_SEND_COMPONENTS_MONTHLY ),
		));

		$subject = new OTGS_Installer_WP_Components_Hooks( $storage, $sender, $settings, $this->get_php_functions_mock() );
		$subject->send_components_data();
	}

	public function get_php_functions_mock() {
		return $this->getMockBuilder( 'OTGS_Installer_PHP_Functions' )
			->setMethods( array( 'phpversion' ) )
			->disableOriginalConstructor()
			->getMock();
	}
}