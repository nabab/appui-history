<?php
use bbn\X;
use bbn\Appui\Grid;
use bbn\Util\Timer;
/**
 * @var bbn\Mvc\Model $model
 */
$timer = new Timer();
$timer->start();

if ($model->hasData(['limit', 'start'])
    && ($history_cfg = $model->getModel('history/config'))
) {
  X::log(['ENTERING DATA', $timer->measure()]);
  $cfg = [
    'table' => 'bbn_history',
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
    'limit' => 50,
    'order' => [
      'tst' => 'DESC'
    ],
    'total' => false,
    'group_by' => [
      'tst',
      'uid',
      'usr',
      'opr'
    ]
  ];

  $grid = new Grid($model->db, $model->data, $cfg);
  $ret = $grid->getDatatable();
  $ret['success'] = true;
  $ret['history'] = $history_cfg['history'];
  foreach ($ret['data'] as &$row) {
    $row['tab_name'] = $history_cfg['tables'][$row['bbn_table']];
    $cols = X::split($row['col'], ',');
    $tmp = [];
    foreach ($cols as $col) {
      $tmp[] = $history_cfg['cols'][strtolower($col)];
    }
    $row['col_name'] = X::join($tmp, ', ');
  }

  return $ret;
}

return [];
