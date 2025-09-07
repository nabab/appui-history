<?php

use bbn\X;
use bbn\Str;
use bbn\Appui\Database;
use bbn\Appui\History;

/** @var bbn\Mvc\Model $model */

// Receiving everything obliges to have already info accessible and therefore granting access for all
if ($model->hasData(['uid', 'col', 'opr', 'tst'], true) &&
  ($cols = explode(',', $model->data['col'])) &&
  ($dbc = new Database($model->db)) &&
  ($table = $dbc->tableFromItem($cols[0])) &&
  ($cfg = $dbc->modelize($table))
) {
  if ($hasHistory = History::isEnabled()) {
    History::disable();
  }

  $cfg['fields'] = array_map(
    function($a, $k) {
      $a['name'] = $k;
      return $a;
    },
    array_values($cfg['fields']),
    array_keys($cfg['fields'])
  );

  $changes = [];
  if ($model->data['opr'] === 'UPDATE') {
    foreach ($cols as $c) {
      $col = X::getField($cfg['fields'], ['id_option' => $c], 'name');
      $changes[$col] = History::getValBack($table, $model->data['uid'], $model->data['tst'] - 1, $col);
    }
  }



  $res = [
    'root' => constant('APPUI_HISTORY_ROOT'),
    'table' => $table,
    'data' => $dbc->getDisplayRecord(
      $table,
      $dbc->getDisplayConfig($table),
      [$cfg['keys']['PRIMARY']['columns'][0] => $model->data['uid']],
      $model->data['tst'],
      $changes
    )
  ];
  if ($hasHistory) {
    History::enable();
  }

  return $res;
}
