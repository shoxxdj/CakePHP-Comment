<?= $this->Form->create('Comment', array('id' => 'commentForm', 'url' => array('controller' => 'comments', 'action' => 'add', 'plugin' => 'comment'))); ?>
	<?= $this->Session->flash('commentForm'); ?>
	<?php if (!$this->Session->read('Auth.'.Configure::read('Plugin.Comment.user.model').'.id')): ?>
		<?= $this->Form->input('username',array('label'=>__('Pseudo'))); ?>
		<?= $this->Form->input('mail',array('label'=>__('Email'),'placeholder' => 'user@domain.com', 'div' => array('class' => 'input text mail'))); ?>
	<?php endif ?>
	<?= $this->Form->input('content',array('label'=>__('Commentaire'),'type' => 'textarea')); ?>
	<?= $this->Form->input('ref',array('type' => 'hidden', 'value' => $ref)); ?>
	<?= $this->Form->input('ref_id',array('type' => 'hidden', 'value' => $ref_id)); ?>
	<?= $this->Form->unlockField('Comment.parent_id'); ?>
	<?= $this->Form->input('parent_id',array('type' => 'hidden', 'default' => 0)); ?>
<?= $this->Form->end(__('Envoyer')); ?>


