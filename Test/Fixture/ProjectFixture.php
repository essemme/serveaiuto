<?php
/**
 * ProjectFixture
 *
 */
class ProjectFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 6),
		'start_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'status_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 6),
		'author_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 6),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 6),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'type_id' => 1,
			'start_date' => '2012-04-20',
			'due_date' => '2012-04-20',
			'status_id' => 1,
			'author_id' => 1,
			'user_id' => 1,
			'parent_id' => 1,
			'lft' => 1,
			'rght' => 1,
			'created' => '2012-04-20 14:44:57',
			'modified' => '2012-04-20 14:44:57'
		),
	);
}
