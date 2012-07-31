<h1> <?php echo $this->Html->image('/CoreAdmin/img/comment.png'); ?> Commentaires</h1>

<div class="bloc">
    <div class="title">Commentaires</div>
    <div class="content">

	<table>
	    <thead>
		<tr>
			<th>ID</th>
		    <th>Nom</th>
		    <th>Email</th>
		    <th>Message</th>
		    <th></th>
		    <th class="actions"><?php echo $this->Html->link("Ajouter un tutoriel",array('action'=>'edit'),array('class'=>'button')); ?></th>
		</tr>
	    </thead>
	    <tbody>
	    	<?php foreach ( $comments as $comment ): ?>
		    	<?= $this->Form->create('Comment'); ?>
		    	<tr>
		    		<td><?= $comment['Comment']['id']; ?></td>
		    		<td><?= h($comment['Comment']['username']); ?></td>
		    		<td><a href="mailto:<?= $comment['Comment']['mail']; ?>"><?= $comment['Comment']['mail']; ?></a></td>
		    		<td>
		    				<?= $this->Form->input('content',array('label'=>false, 'value' => $comment['Comment']['content'])); ?>
		    				<?= $this->Form->input('id'); ?>
		    		</td>
		    		<td><?= $this->Form->submit('Editer'); ?></td>
		    		<td>
		    			<?= $this->Html->link(
		    				$this->Html->image('/CoreAdmin/img/delete.png'),
		    				array('action'=>'delete', $comment['Comment']['id']),
		    				array('escape' => false),
		    				'Voulez vous vraiment supprimer ce commentaire ?'
		    			); ?>
		    		</td>
		    	</tr>
		    	<?= $this->Form->end(); ?>
	    	<?php endforeach ?>
	    </tbody>
	</table>

	<div class="pagination">
	    <?php echo $this->Paginator->numbers(array('separator'=>false)); ?>
	</div>

    </div>
</div>