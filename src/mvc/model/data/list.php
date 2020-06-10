<?php
if ($model->has_data(['limit', 'start'])) {
  $cfg = [
    'tables' => 'bbn_history',
    'fields' => [
      'uid',
      'tst',
      'opr',
      'usr',
      'dt',
      'tab_name' => 'ot.text',
      'bbn_table' => 'bbn_history_uids.bbn_table',
      'col_name' => 'oc.text',
      'col'
    ],
    'join' => [
      [
        'table' => 'bbn_history_uids',
        'on' => [[
          'field' => 'bbn_uid',
          'exp' => 'uid'
        ]]
      ], [
        'table' => 'bbn_options',
        'alias' => 'ot',
        'on' => [[
          'field' => 'ot.id',
          'exp' => 'bbn_table'
        ]]
      ], [
        'table' => 'bbn_options',
        'alias' => 'oc',
        'on' => [[
          'field' => 'oc.id',
          'exp' => 'col'
        ]]
      ]
    ],
    'start' => $model->data['start'],
    'order' => [
      'tst' => 'DESC'
    ]
  ];
  if (!empty($model->data['filters'])) {
    $cfg['where'] = $model->data['filters'];
  }
  if (!empty($model->data['order'])) {
    $cfg['order'] = $model->data['order'];
  }
  $tot = 0;
  $start = $model->data['start'];
  $combi = null;
  $num = -1;
  $res = [];
  while ($row = $model->db->rselect($cfg)) {
    $cfg['start']++;
    $tmp = $row['uid'].'-'.$row['opr'].'-'.$row['usr'].'-'.$row['tst'];
    if ($tmp !== $combi) {
      $combi = $tmp;
      $num++;
      if ($num === $model->data['limit']) {
        break;
      }
      else {
        $res[$num] = $row;
      }
    }
    else if (!empty($res[$num])) {
      $res[$num]['col_id'] .= ','.$row['col_id'];
      $res[$num]['col_name'] .= ','.$row['col_name'];
    }
  }
  $count_cfg = [
    'tables' => ['bbn_history'],
    'fields' => ['COUNT(DISTINCT uid, usr, tst, opr)'],
    'join' => [
      [
        'table' => 'bbn_history_uids',
        'on' => [[
          'field' => 'bbn_uid',
          'exp' => 'uid'
        ]]
      ], [
        'table' => 'bbn_options',
        'alias' => 'ot',
        'on' => [[
          'field' => 'ot.id',
          'exp' => 'bbn_table'
        ]]
      ], [
        'table' => 'bbn_options',
        'alias' => 'oc',
        'on' => [[
          'field' => 'oc.id',
          'exp' => 'col'
        ]]
      ]
    ]
  ];
  if (!empty($model->data['filters'])) {
    $count_cfg['where'] = $model->data['filters'];
  }
  $ret = [
    'data' => $res,
    'total' => $model->db->select_one($count_cfg),
    'success' => true,
    'error' => false
  ];
  return $ret;
}