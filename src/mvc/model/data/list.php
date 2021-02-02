<?php
use bbn\X;
/**
 * @var bbn\Mvc\Model $model
 */
$timer = new \bbn\Util\Timer();
$timer->start();

if ($model->hasData(['limit', 'start'])
    && ($history_cfg = $model->getModel('history/config'))
) {
  X::log(['ENTERING DATA', $timer->measure()]);
  $cfg = [
    'tables' => 'bbn_history',
    'fields' => [
      'uid',
      'tst',
      'opr',
      'usr',
      'dt',
      'bbn_table',
      'col' => "GROUP_CONCAT(HEX(col) separator ',')"
    ],
    'join' => [
      [
        'table' => 'bbn_history_uids',
        'on' => [[
          'field' => 'bbn_uid',
          'exp' => 'uid'
        ]]
      ]
    ],
    'start' => $model->data['start'],
    'limit' => $model->data['limit'],
    'order' => [
      'tst' => 'DESC'
    ],
    'group_by' => [
      'tst',
      'uid',
      'usr',
      'opr'
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
  //x::log(['BEFORE DATA', $timer->measure()]);
  $rows = $model->db->rselectAll($cfg);
  //x::log(['AFTER DATA', $timer->measure()]);
  foreach ($rows as &$row) {
    $row['tab_name'] = $history_cfg['tables'][$row['bbn_table']];
    $cols = X::split($row['col'], ',');
    $tmp = [];
    foreach ($cols as $col) {
      $tmp[] = $history_cfg['cols'][strtolower($col)];
    }
    $row['col_name'] = X::join($tmp, ', ');
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
      ]
    ]
  ];
  if (!empty($model->data['filters'])) {
    $count_cfg['where'] = $model->data['filters'];
  }
  //x::log(['BEFORE COUNT', $timer->measure()]);
  $count = $model->db->selectOne($count_cfg);
  //x::log(['AFTER COUNT', $timer->measure()]);
  $ret = [
    'history' => $history_cfg['history'],
    'data' => $rows,
    'total' => $count,
    'success' => true,
    'error' => false
  ];
  return $ret;
}
return [];