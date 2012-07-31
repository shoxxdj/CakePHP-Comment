<?php
class CommentableBehavior extends ModelBehavior{

	public function setup(Model $model,$config = array()){
		$model->hasMany['Comment'] = array(
			'className'  => 'Comment.Comment',
			'foreignKey' => 'ref_id',
			'order'		 => 'Comment.created ASC',
			'conditions' => 'ref = "'.$model->name.'"',
			'dependent'  => true
		);
	}

	/**
	* Find all comments related to the current Model (id has to be specified inside the model)
	* @param Model $model
	* @param array $options Same options as Model::find() method
	**/
	public function findComments($model,$options = array()){
		$ref = $model->name;
		$ref_id = $model->id;
		return $model->Comment->findRelated($ref,$ref_id,$options);
	}

}
