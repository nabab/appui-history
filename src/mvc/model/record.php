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
){
  $displayCfg = $dbc->getDisplayConfig($table);
  //X::ddump($displayCfg, $table);
  
  History::disable();
  $res = [
    'root' => APPUI_HISTORY_ROOT,
    'table' => $table,
    'data' => $dbc->getDisplayRecord($table, $displayCfg, [$cfg['keys']['PRIMARY']['columns'][0] => $model->data['uid']])
  ];
  History::enable();
  return $res;
}
