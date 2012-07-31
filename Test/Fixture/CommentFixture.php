<?php
/**
 * CommentFixture
 *
 */
class CommentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'mail' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ref' => array('type' => 'string', 'null' => true, 'default' => 'tutoriel', 'length' => 60, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ref_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4, 'key' => 'index'),
		'ip' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 9),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 9),
		'spam' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 1),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'tutoriel_id' => array('column' => 'ref_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'username' => '',
			'mail' => '',
			'content' => 'Comment1',
			'ref' => 'Post',
			'ref_id' => 1,
			'ip' => '192.0.0.0',
			'created' => '2012-07-18 08:58:12',
			'parent_id' => 0,
			'user_id' => 1,
			'spam' => 0
		),
		array(
			'id' => 2,
			'username' => 'Test',
			'mail' => 'contact@test.fr',
			'content' => 'Comment2',
			'ref' => 'Post',
			'ref_id' => 1,
			'ip' => '192.0.0.0',
			'created' => '2012-07-18 08:58:12',
			'parent_id' => 0,
			'user_id' => 1,
			'spam' => 0
		),
		array(
			'id' => 3,
			'username' => 'Test',
			'mail' => 'contact@test.fr',
			'content' => 'Comment3',
			'ref' => 'Post',
			'ref_id' => 1,
			'ip' => '192.0.0.0',
			'created' => '2012-07-18 08:58:12',
			'parent_id' => 1,
			'user_id' => 1,
			'spam' => 0
		),
	);

}
