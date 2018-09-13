<?php
$res = ['success' => false];
if (
  !empty($model->data['uid']) &&
  !empty($model->data['tst']) &&
  !empty($model->data['usr']) &&
  !empty($model->data['col']) &&
  ($id_table = $model->db->select_one('bbn_history_uids', 'bbn_table', ['bbn_uid' => $model->data['uid']]))
  //($id_table = $model->db->select_one('bbn_history_uids', 'id_table', ['uid' => $model->data['uid']]))
){
  $table = $model->inc->options->code($id_table);
  $dbc = new \bbn\appui\databases($model->db);
  $m = $dbc->modelize($table);

  $rows = $model->db->rselect_all('bbn_history', [], [
    'uid' => $model->data['uid'],
    'col' => $model->data['col'],
    'tst' => $model->data['tst'],
    'usr' => $model->data['usr']
  ]);
  $h =& \bbn\appui\history::$column;
  $primary = $model->db->get_primary($table)[0];
  $errors = [];
  foreach ( $rows as $hist ){
    switch ( $hist['opr'] ){
      case 'INSERT':
        if ( !$model->db->delete($table, [$primary => $hist['uid']]) ){
         $errors[] = $hist;
        }
        break;
      case 'UPDATE':
        if ( ($col = $model->inc->options->code($hist['col'])) ){
          $old = $m['fields'][$col]['type'] === 'binary' ? $hist['ref'] : $hist['val'];
          if ( !$model->db->update($table, [$col => $old], [$primary => $hist['uid']]) ){
            $errors[] = $hist;
          }
        }
        else {
          $errors[] = $hist;
        }
        break;
      case 'DELETE':
        if ( !$model->db->update($table, [$h => 1], [$primary => $hist['uid'], $h => 0]) ){
          $errors[] = $hist;
        }
        break;
      case 'RESTORE':
        if ( !$model->db->delete($table, [$primary => $hist['uid']]) ){
          $errors[] = $hist;
        }
        break;
    }
  }
  if ( empty($errors) ){
    $res['success'] = true;
  }
  else {
    $res['errors'] = $errors;
  }
}
return $res;