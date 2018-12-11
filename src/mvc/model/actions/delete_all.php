<?php
/*
 * Describe what it does!
 *
 **/

//\bbn\appui\history::disable();

/** @var $this \bbn\mvc\model*/
if ( !empty($uid = $model->data['uid']) ){
  $success = false;
  $count = 0;
 	//$count = $model->db->delete('bbn_history', ['uid' => $uid]);
  //$success = $model->db->delete('bbn_history_uids', ['bbn_uid' => $model->data['uid']]);
  $success = \bbn\appui\history::delete($model->data['uid']);
//  \bbn\appui\history::enable();
  return [
    'success' => $success
	];
}