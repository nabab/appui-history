<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( !empty($uid = $model->data['uid']) ){
  $success = false;
  $row = $model->db->rselect('bbn_history_uids', ['bbn_table', 'bbn_active'], ['bbn_uid' =>  $uid]);
 
  $table = $row['bbn_table'];
  $active = $row['bbn_active'];
  
  $table_name = $model->inc->options->text($table);
  if ( $opr = $model->db->rselect_all([
    'table' => 'bbn_history',
    'fields' => [
      'col' => 'bbn_options.text', 
      'opr', 'val', 'ref', 'tst', 'usr', 'dt'
    ],
    'where' => [
      'uid'=> $uid
    ],
    'join' => [[
      'table' => 'bbn_options',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_history.col',
          'operator' => 'eq',
          'exp' => 'bbn_options.id'
        ]],
        'logic' => 'AND'
      ]
    ]],
    'order' => [['field' => 'tst', 'dir' => 'DESC']]
  ]) ){
    if( !empty($table_name) ){
      $success = true;  
    }
  }
  else {
    $opr = []; 
  }
  
  
  
  
 
  
  return [
    'active' => $active,
    'success' => $success,
    'table' => $table_name,
    'opr' => $opr
  ];
}