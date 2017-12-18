<?php
$res = ['success' => false];
if (
  isset($model->data['uid'], $model->data['chrono'], $model->data['id_user'], $model->data['column']) &&
  ($id_table = $model->db->select_one('bbn_history_uids', 'id_table', ['uid' => $model->data['uid']]))
){
  $table = $model->inc->options->code($id_table);
  $dbc = new \bbn\appui\databases($model->db);
  $model = $dbc->modelize($table);
  $rows = $model->db->rselect_all('bbn_history', [], [
    'line' => $model->data['uid'],
    'column' => $model->data['column'],
    'chrono' => $model->data['chrono'],
    'id_user' => $model->data['id_user']
  ]);
  $h =& \bbn\appui\history::$column;
  $primary = $model->db->get_primary($table);
  foreach ( $rows as $hist ){
    switch ( $hist['operation'] ){
      case 'INSERT':
        $res['success'] = $model->db->delete($table, [$primary => $hist['line']]);
        break;
      case 'UPDATE':
        $res['success'] = $model->db->update($table, [$col => $hist['old']], [$primary => $hist['line']]);
        break;
      case 'DELETE':
        $res['success'] = $model->db->update($table, [$h => 1], [$primary => $hist['line'], $h => 0]);
        break;
      case 'RESTORE':
        $res['success'] = $model->db->delete($table, [$primary => $hist['line']]);
        break;
    }
  }
}
return $res;