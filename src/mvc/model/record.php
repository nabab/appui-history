<?php

use bbn\X;
use bbn\Str;
use bbn\Appui\Database;
use bbn\Appui\History;

/** @var bbn\Mvc\Model $model */

// Receiving everything obliges to have already info accessible and therefore granting access for all
if ($model->hasData(['uid', 'col'], true) &&
  ($cols = explode(',', $model->data['col'])) &&
  ($dbc = new Database($model->db)) &&
  ($table = $dbc->tableFromItem($cols[0])) &&
  ($cfg = $dbc->modelize($table))
) {
  if ($hasHistory = History::isEnabled()) {
    History::disable();
  }

  $when = $model->hasData('dt', true) ? date('Y-m-d H:i:s', (Str::isNumber($model->data['dt']) ? $model->data['dt'] : strtotime($model->data['dt'])) + 1) : null;
  $res = [
    'root' => APPUI_HISTORY_ROOT,
    'table' => $table,
    'data' => $dbc->getDisplayRecord($table, $dbc->getDisplayConfig($table), [$cfg['keys']['PRIMARY']['columns'][0] => $model->data['uid']], $when),
  ];
  if ($hasHistory) {
    History::enable();
  }

  return $res;
}
