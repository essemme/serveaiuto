<?php
App::uses('Type', 'Model');

/**
 * Type Test Case
 *
 */
class TypeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.type', 'app.project');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Type = ClassRegistry::init('Type');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Type);

		parent::tearDown();
	}

}
