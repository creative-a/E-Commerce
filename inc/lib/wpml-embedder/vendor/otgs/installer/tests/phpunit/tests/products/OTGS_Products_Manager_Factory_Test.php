<?php

use PHPUnit\Framework\TestCase;

class OTGS_Products_Manager_Factory_Test extends TestCase {
	/**
	 * @test
	 */
	public function it_creates_products_manager() {
		$repositories_config = $this->createMock(OTGS_Products_Config_Xml::class);
		$logger_storage = $this->createMock(OTGS_Installer_Logger_Storage::class);
		$result = OTGS_Products_Manager_Factory::create( $repositories_config, $logger_storage );

		$this->assertInstanceOf(OTGS_Products_Manager::class, $result);
	}
}
