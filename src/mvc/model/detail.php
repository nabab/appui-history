<?php
/** @var $model \bbn\mvc\model */
if ( !empty($model->data['uid']) &&
  !empty($model->data['col']) &&
  ($cols = explode(',', $model->data['col'])) &&
  !empty($model->data['tst']) &&
  !empty($model->data['usr']) &&
  ($dbc = new \bbn\appui\database($model->db)) &&
  ($table = $dbc->table_from_item($cols[0])) &&
  ($cfg = $model->db->modelize($table)) &&
  isset($cfg['keys']['PRIMARY']) &&
  (count($cfg['keys']['PRIMARY']['columns']) === 1)
){
  $hist = [];
  $fix_value = function($val, $column) use($cfg, $model){
    if ( isset($cfg['keys'][$column]) && !empty($cfg['keys'][$column]['ref_column']) ){
      if ( $cfg['keys'][$column]['ref_table'] === 'bbn_options' ){
        $val = $model->inc->options->text($val);
      }
      else if ( \bbn\str::is_json($val) ){

      }
    }
    return $val;
  };
  foreach ( $cols as $col ){
    $tmp = $model->db->rselect('bbn_history', [], [
      'uid' => $model->data['uid'],
      'col' => $col,
      'tst' => $model->data['tst'],
      'usr' => $model->data['usr']
    ]);
    $column = $model->inc->options->text($col);
    $new = \bbn\appui\history::get_val_back($table, $tmp['uid'], $tmp['tst'] + 1, $column);
    $hist[] = [
      'column' => ucwords(str_replace('_', ' ', $column)),
      'before' => $fix_value($tmp['ref'] ?? $tmp['val'], $column),
      'after' => $fix_value($new, $column)
    ];
  }
  if ( !empty($hist) ){
    return [
      'root' => APPUI_HISTORY_ROOT,
      'uid' => $model->data['uid'],
      'operation' => $tmp['opr'],
      'table' => $table,
      'id_user' => $model->data['usr'],
      'items' => $hist
    ];
  }
}