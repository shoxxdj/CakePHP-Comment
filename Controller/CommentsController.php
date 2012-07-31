<?php
class CommentsController extends AppController{

	public function beforeFilter(){
		parent::beforeFilter();
		extract(Configure::read('Plugin.Comment.user'));
		$this->paginate = array(
			'fields' => array("Comment.username","Comment.mail","$model.$username","$model.$mail","Comment.content","Comment.id"),
			'contain'=> array($model),
			'order'   => 'Comment.id DESC'
		);
	}

	function add(){
		$referer = $this->referer().'#commentForm';
		if(!empty($this->request->data)){
			$user_id = $this->Auth->user("id");

			// We add new datas
			$this->request->data['Comment']['ip'] = $this->getIp();
			$this->request->data['Comment']['user_id'] = $user_id ? $user_id : 0;

			$this->Comment->create($this->request->data, true);

			$model = ClassRegistry::init($this->request->data['Comment']['ref']);

			// Can we comment this model ?
			if(!$model->Behaviors->attached('Commentable')){
				$this->Session->setFlash(__("Impossible de commenter ce contenu"), "flash", array('class' => 'error'), 'commentForm');
				return $this->redirect($referer);
			}

			// Does this record exists ?
			if(!$model->exists($this->request->data['Comment']['ref_id'])){
				$this->Session->setFlash(__("Impossible de commenter ce contenu"), "flash", array('class' => 'error'), 'commentForm');
				return $this->redirect($referer);
			}

			if($this->request->data['Comment']['parent_id'] != 0 && !$this->Comment->exists($this->request->data['Comment']['parent_id'])){
				$this->Session->setFlash(__("Impossible de répondre à ce commentaire"), "flash", array('class' => 'error'), 'commentForm');
			}

			// Does this comment validates ?
			if( $this->Comment->validates() || ($user_id && !empty($this->request->data['Comment']['content'])) ){

				// Spam is configured ?
				if(Configure::read('Plugin.Comment.akismet') && !$user_id){
					$is_spam = $this->Comment->isSpam();
					if(!Configure::read('Plugin.Comment.keepSpam') && $is_spam){
						$this->Session->setFlash(__("Ce commentaire a été considéré comme spam et ne sera pas sauvegardé"), "flash", array('class' => 'error'), 'commentForm');
						return $this->redirect($referer);
					}elseif($is_spam){
						$this->Comment->data['Comment']['spam'] = 1;
					}
				}

				$this->Session->setFlash(__("<strong>Merci</strong> votre commentaire a bien été sauvegardé"), "flash", array('class' => 'success'), 'commentForm');
				$this->Comment->save(null, false);
				return $this->redirect($referer);

			}else{

				$this->Session->setFlash(__("Certains champs n'ont pas été rempli correctement, merci de corriger vos erreurs"), "flash", array('class' => 'error'), 'commentForm');
				return $this->redirect($referer);

			}
		}
	}

	private function getIp(){
		$ip;
		if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
		else
		$ip = false;
		return $ip;
	}

	function admin_index(){
		$comments = $this->paginate('Comment');
		$this->set(compact('comments'));
	}

	function admin_delete( $id ){
		$this->Comment->delete($id);
		return $this->redirect($this->referer());
	}

}