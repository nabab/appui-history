<?php
/** @var $model \bbn\mvc\model */
if ( !empty($model->data['uid']) &&
  !empty($model->data['col']) &&
  !empty($model->data['tst']) &&
  ($hist = $model->db->rselect('bbn_history', [], [
    'uid' => $model->data['uid'],
    'col' => $model->data['col'],
    'tst' => $model->data['tst']
  ])) &&
  ($dbc = new \bbn\appui\databases($model->db))
){
  $table = $dbc->table_from_item($model->data['col']);
  $column = $model->inc->options->text($model->data['col']);
  $cfg = $model->db->modelize($table);
  if ( $cfg &&
    isset($cfg['keys']['PRIMARY']) &&
    (count($cfg['keys']['PRIMARY']['columns']) === 1)
  ){
    $primary = $cfg['keys']['PRIMARY']['columns'][0];
    $new = \bbn\appui\history::get_val_back($table, $hist['uid'], $hist['tst'] + 1, $column);
    $tiers = new \apst\tiers($model->db);
    $lieux = new \apst\lieux($model->db);
    $fix_value = function($val) use($cfg, $column, $tiers, $lieux, $model){
      if ( isset($cfg['keys'][$column]) && !empty($cfg['keys'][$column]['ref_column']) ){
        if ( $column === 'id_lieu' ){
          $val = $lieux->fadresse($val);
        }
        else if ( $column === 'id_tiers' ){
          $val = $val.' '.$tiers->fnom($val);
        }
        else if ( $cfg['keys'][$column]['ref_table'] === 'bbn_options' ){
          $val = $model->inc->options->text($val);
        }
      }
      return $val;
    };
    return [
      'root' => APPUI_HISTORY_ROOT,
      'uid' => $hist['uid'],
      'operation' => $hist['opr'],
      'table' => $table,
      'column' => ucwords(str_replace('_', ' ', $column)),
      'id_user' => $hist['usr'],
      'before' => $fix_value($hist['ref'] ?: $hist['val']),
      'after' => $fix_value($new)
    ];
  }
}