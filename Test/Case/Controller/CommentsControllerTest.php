<?php
App::uses('CommentsController', 'Comment.Controller');
App::uses('AppModel','Model');

class Post extends AppModel {
	public $alias = 'Post';
	public $useTable = 'posts';
	public $actsAs = array('Containable','Comment.Commentable');
}
class User extends AppModel{

}

/**
 * CommentsController Test Case
 *
 */
class CommentsControllerTest extends ControllerTestCase {

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
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		$data = array('Comment' => array(
			'username' => 'Grafikart',
			'mail'	   => 'contact@test.fr',
			'content'  => 'Salut ça va ?',
			'ref'	   => 'Post',
			'ref_id'   => 1,
			'parent_id'=> 0
		));
		$result = $this->testAction('/comment/comments/add', array('data' => $data, 'method' => 'post'));
		$comments = $this->Comment->find('count',array(
			'conditions' => array('ref' => 'Post', 'ref_id' => 1)
		));
		$this->assertEquals($comments, 4);
	}


	public function testAddWithUserLogged() {
	    $Comments = $this->generate('Comments', array(
	        'components' => array(
	            'Session',
	            'Auth' => array('user')
	        )
	    ));
	    $Comments->Auth->staticExpects($this->any())
	        ->method('user')
	        ->with('id')
	        ->will($this->returnValue(3));
		$data = array('Comment' => array(
			'content'  => 'Salut ça va ?',
			'ref'	   => 'Post',
			'ref_id'   => 1,
			'parent_id'=> 0
		));
		$result = $this->testAction('/comment/comments/add', array('data' => $data, 'method' => 'post'));
	    $comment = $this->Comment->find('first',array(
			'conditions' => array('ref' => 'Post', 'ref_id' => 1,'user_id' => 3)
		));
	    $this->assertEquals($comment['Comment']['content'], $data['Comment']['content']);
	}

	public function testAddWithBadMail() {
		$data = array('Comment' => array(
			'username' => 'Grafikart',
			'mail'	   => 'contactbadfr',
			'content'  => 'Salut ça va ?',
			'ref'	   => 'Post',
			'ref_id'   => 1,
			'parent_id'=> 0
		));
		$result = $this->testAction('/comment/comments/add', array('data' => $data, 'method' => 'post'));
		$comments = $this->Comment->find('count',array(
			'conditions' => array('ref' => 'Post', 'ref_id' => 1)
		));
		$this->assertEquals($comments, 3);
	}

}
