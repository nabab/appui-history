<?php
use bbn\appui\history;

return $model->get_set_from_cache(function () use (&$model) {
  $cfg = history::get_db_cfg();
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