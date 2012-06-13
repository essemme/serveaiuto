<?php
App::uses('Status', 'Model');

/**
 * Status Test Case
 *
 */
class StatusTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.status', 'app.project');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Status = ClassRegistry::init('Status');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Status);

		parent::tearDown();
	}

}
