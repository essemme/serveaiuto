<?php
App::uses('Media', 'Model');

/**
 * Media Test Case
 *
 */
class MediaTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.media', 'app.user', 'app.project', 'app.type', 'app.status');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Media = ClassRegistry::init('Media');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Media);

		parent::tearDown();
	}

}
