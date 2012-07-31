<?php
class CommentHelper extends AppHelper{

    public $helpers	 = array('Html','Form');

    public function form($ref, $ref_id){
    	return $this->_View->element('form',array('ref' => $ref, 'ref_id' => $ref_id), array('plugin' => 'Comment'));
    }

}
