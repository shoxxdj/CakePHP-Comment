<?php
App::uses('Comment', 'Comment.Model');
App::uses('AppModel', 'Model');

class Post extends AppModel {
	public $alias = 'Post';
	public $useTable = 'posts';
	public $actsAs = array('Containable','Comment.Commentable');
}
class User extends AppModel{

}

/**
 * Comment Test Case
 *
 */
class CommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.comment.comment',
		'plugin.comment.post',
		'plugin.comment.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		require APP.'Plugin'.DS.'Comment'.DS.'Config'.DS.'bootstrap.php';
		$default['models'] = array('Post');
		Configure::write('Plugin.Comment', $default);
		$this->Comment = ClassRegistry::init('Comment.Comment');
		$this->Post = ClassRegistry::init('Post');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Comment);
		parent::tearDown();
	}

	public function testEmpty(){
		$this->Comment->deleteAll('1=1');
		$comments = $this->Comment->findRelated('Post',1);
		$this->assertEqual($comments, array());
	}

	public function testConfig() {
		$conf = Configure::read('Plugin.Comment');
		$this->assertEqual($conf['models'], array('Post'));
	}

	public function testBelongsToUser(){
		$comment = $this->Comment->find('first', array(
			'conditions' => array('Comment.user_id' => 1),
			'contain' 	 => array('User' => array('id','username'))
		));
		$this->assertEqual($comment['User']['username'], 'User1');
	}

	public function testAfterFind(){
		$comment = $this->Comment->find('first', array(
			'conditions' => array('Comment.user_id' => 1),
			'contain' 	 => array('User' => array('username','mail'))
		));
		$this->assertEqual($comment['Comment']['username'], 'User1');
		$this->assertEqual($comment['Comment']['mail'], 'User1@test.fr');
	}

	public function testCounter(){
		$this->Comment->save(array(
			'ref' => 'Post',
			'ref_id' => 1
		), false);
		$this->Post->id = 1;
		$this->assertEqual($this->Post->field('comment_count'), 4);
	}

/**
 * testFindRelated method
 *
 * @return void
 */
	public function testFindRelated() {
		$comments = $this->Comment->findRelated('Post',1);
		$this->assertEqual($comments[1]['Comment']['id'], 1);
		$this->assertEqual($comments[2]['Comment']['id'], 2);
		$this->assertEqual($comments[1]['Answer'][0]['Comment']['id'], 3);
	}
	public function testFindCommentsFromBehavior() {
		$this->Post->id = 1;
		$comments = $this->Post->findComments();
		$this->assertEqual($comments[1]['Comment']['id'], 1);
		$this->assertEqual($comments[2]['Comment']['id'], 2);
		$this->assertEqual($comments[1]['Answer'][0]['Comment']['id'], 3);
	}

}
