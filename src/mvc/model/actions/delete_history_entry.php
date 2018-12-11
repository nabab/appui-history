<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
//bbn\appui\history::disable();

if ( !empty($entry = $model->data['entry']) && !empty($model->data['uid']) ){
  $success = false;
  $success = $model->db->delete('bbn_history', [
    'uid' => $model->data['uid'],
    'dt' => $entry['dt'],
    'opr' => $entry['opr'],
    'tst' => $entry['tst'],
    'usr' => $entry['usr']
  ] );
  //bbn\appui\history::enable();

  return [
    'success' => $success
  ];
}