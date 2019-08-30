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
        \bbn\appui\history::disable();
        if ( $original = $model->db->rselect($table, [], [$primary => $model->data['uid']]) ){
          \bbn\appui\history::enable();
          if ( !$model->db->insert($table, $original) ){
            $errors[] = $hist;
          }
        }
        else {
          \bbn\appui\history::enable();
          $errors[] = $hist;
        }
        /* if ( !$model->db->update([
          'table' => 'bbn_history_uids',
          'fields' => [$h => 1],
          'where' => [
            'conditions' => [[
              'field' => 'bbn_uid',
              'value' => $hist['uid']
            ]]
          ]
        ]) ){ 
          $errors[] = $hist;
        }
        */
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