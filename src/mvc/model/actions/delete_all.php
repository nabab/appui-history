<?php
/*
 * Describe what it does!
 *
 **/

//\bbn\Appui\History::disable();

/** @var $this \bbn\Mvc\Model*/
if ( !empty($uid = $model->data['uid']) ){
  $success = false;
  $count = 0;
 	//$count = $model->db->delete('bbn_history', ['uid' => $uid]);
  //$success = $model->db->delete('bbn_history_uids', ['bbn_uid' => $model->data['uid']]);
  $success = \bbn\Appui\History::delete($model->data['uid']);
//  \bbn\Appui\History::enable();
  return [
    'success' => $success
	];
}