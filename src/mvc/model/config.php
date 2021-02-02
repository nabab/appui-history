<?php
use bbn\Appui\History;

return $model->getSetFromCache(function () use (&$model) {
  $cfg = History::getDbCfg();
  $tables = [];
  $cols = [];
  foreach ($cfg as $table => $o) {
    $tables[$o['id']] = $table;
    foreach ($o['fields'] as $col => $f) {
      $cols[$f['id_option']] = $col;
    }
  }
  return [
    'tables' => $tables,
    'cols' => $cols,
    'history' => $cfg
  ];
}, [], '', 3600);