<?php
class Comment extends AppModel{

	public $actsAs = array('Containable');
	public $recursive = -1;
	public $validate = array(
		'username' => array(
			'rule' => 'notEmpty',
			'required' => true
		),
		'mail' => array(
			'rule' => 'email',
			'required' => true
		),
		'content' => array(
			'rule' => 'notEmpty',
			'required' => true
		)
	);

	public function __construct( $id = false, $table = NULL, $ds = NULL ){
		$this->order = Configure::read('Plugin.Comment.order');
		$this->belongsTo['User'] = array(
			'className' => Configure::read('Plugin.Comment.user.model'),
			'foreignKey'=> 'user_id',
		);
		parent::__construct($id,$table,$ds);
	}

	public function isSpam(){
		require APP . 'Plugin' . DS . 'Comment' . DS . 'Vendor' . DS . 'akismet.php';
		App::uses('Akismet','Vendor');
		$akismet = new Akismet(Configure::read('Plugin.Comment.akismet.site'), Configure::read('Plugin.Comment.akismet.key'));
		$akismet->setCommentAuthor($this->data['Comment']['username']);
        $akismet->setCommentAuthorEmail($this->data['Comment']['mail']);
        $akismet->setCommentContent($this->data['Comment']["content"]);
        $akismet->setUserIP($this->data['Comment']['ip']);
        return $akismet->isCommentSpam();
	}

	/**
	* AfterSave
	**/
	public function afterSave($created){
		if ($created) {
			$model = ClassRegistry::init($this->data[$this->alias]['ref']);
			// Manual countercache, it's more flexible, no need to add complex relation on Comment Model
			if($model->hasField('comment_count')){
				$model->id = $this->data[$this->alias]['ref_id'];
				$model->saveField('comment_count',$this->find('count',array(
					'conditions' => array('ref'=>$this->data[$this->alias]['ref'],'ref_id'=>$this->data[$this->alias]['ref_id'])
				)));
			}
			$this->getEventManager()->dispatch(new CakeEvent('Plugin.Comment.add', $this));
		}
	}


	public function afterFind($results, $primary = false){
		foreach($results as $k => $result){
			if(isset($result['User'][Configure::read('Plugin.Comment.user.username')])){
				$results[$k][$this->alias]['username'] = $result['User'][Configure::read('Plugin.Comment.user.username')];
			}
			if(isset($result['User'][Configure::read('Plugin.Comment.user.mail')])){
				$results[$k][$this->alias]['mail'] = $result['User'][Configure::read('Plugin.Comment.user.mail')];
			}
		}
		return $results;
	}


	/**
	* Find all comments related to a specific content
	* @param string $ref Model linked with comments
	* @param int $ref_id ID of the content linked with the comments
	* @todo  Supprimer les commentaires inline : si le code est suffisamment clair cela se lit tout seul, sinon séparer en sous méthodes protected
	**/
	public function findRelated($ref,$ref_id,$options = array()){
		// We had the conditions to find linked comments only
		$options['conditions']['ref'] = $ref;
		$options['conditions']['ref_id'] = $ref_id;

		// We need to retrieve User informations
		if(!isset($options['contain']['User'])){
			$fields = Configure::read('Plugin.Comment.user');
			unset($fields['model']);
			$fields[] = 'id';
			$fields = array_values($fields);
			$options['contain']['User'] = $fields;
		}

		$comments = $this->find('all',$options);


		if(Configure::read('Plugin.Comment.subcomments')){
			$comments = Hash::combine($comments,'{n}.Comment.id','{n}','{n}.Comment.parent_id');
			if(!isset($comments[0])){
				return array();
			}
			foreach($comments[0] as $k => $coms){
				$comments[0][$k]['Answer'] = array();
			}
			foreach($comments as $parent_id => $coms){
				if($parent_id != 0){
					$comments[0][$parent_id]['Answer'] = Hash::sort($coms,'{n}.Comment.id','ASC');
				}
			}
			return $comments[0];
		}else{
			return $comments;
		}
	}

}