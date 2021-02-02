<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\Mvc\Model*/
//bbn\Appui\History::disable();

if ( !empty($entry = $model->data['entry']) && !empty($model->data['uid']) ){
  $success = false;
  $success = $model->db->delete('bbn_history', [
    'uid' => $model->data['uid'],
    'dt' => $entry['dt'],
    'opr' => $entry['opr'],
    'tst' => $entry['tst'],
    'usr' => $entry['usr']
  ] );
  //bbn\Appui\History::enable();

  return [
    'success' => $success
  ];
}