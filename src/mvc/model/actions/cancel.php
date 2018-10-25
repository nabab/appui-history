<?php
if (
  !empty($model->data['uid']) &&
  !empty($model->data['tst']) &&
  !empty($model->data['usr']) &&
  !empty($model->data['col']) &&
  ($cols = explode(',', $model->data['col'])) &&
  ($id_table = $model->db->select_one('bbn_history_uids', 'bbn_table', ['bbn_uid' => $model->data['uid']]))
){
  $table = $model->inc->options->code($id_table);
  $dbc = new \bbn\appui\databases($model->db);
  $m = $dbc->modelize($table);
  $h =& \bbn\appui\history::$column;
  $primary = $model->db->get_primary($table)[0];
  $errors = [];

  $rows = $model->db->rselect_all('bbn_history', [], [
    'uid' => $model->data['uid'],
    'col' => $cols,
    'tst' => $model->data['tst'],
    'usr' => $model->data['usr']
  ]);
  
  if ( !empty($rows) ){
    $hist = [
     'uid' => $model->data['uid'],
     'opr' => $rows[0]['opr'],
     'cols' => []
    ];
    foreach ( $rows as $row ){
      if ( ($col = $model->inc->options->code($row['col'])) ){
        $hist['cols'][$col] = $m['fields'][$col]['type'] === 'binary' ? $row['ref'] : $row['val'];
      }
    }
    switch ( $hist['opr'] ){
      case 'INSERT':
        if ( !$model->db->delete($table, [$primary => $hist['uid']]) ){
         $errors[] = $hist;
        }
        break;
      case 'UPDATE':
        if ( !$model->db->update($table, $hist['cols'], [$primary => $hist['uid']]) ){
          $errors[] = $hist;
        }
        break;
      case 'DELETE':
        if ( !$model->db->update('bbn_history_uids', [$h => 1], [$primary => $hist['uid'], $h => 0]) ){
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
    return ['success' => true];
  }
  else {
    return [
      'success' => false,
      'errors' => $errors
    ];
  }
}