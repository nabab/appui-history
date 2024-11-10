<?php
/** @var bbn\Mvc\Model $model */
$res = [
  'root' => APPUI_HISTORY_ROOT,
  'tables' => [],
  'columns' => []
];
$dbc = new \bbn\Appui\Database($model->db);
foreach ( $dbc->tables() as $i => $table ){
  $res['tables'][] = ['value' => $table['id'], 'text' => $table['text']];
  foreach ( $dbc->columns($table['id']) as $id => $col ){
    $res['columns'][] = ['value' => $id, 'text' => $table['text'].'.'.$col];
  }
}
return $res;

