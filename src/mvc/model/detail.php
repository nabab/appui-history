<?php

use bbn\X;
use bbn\Str;
use bbn\Appui\Database;
use bbn\Appui\History;

/** @var bbn\Mvc\Model $model */

// Receiving everything obliges to have already info accessible and therefore granting access for all
if ($model->hasData(['uid', 'col', 'tst', 'usr'], true) &&
  ($cols = explode(',', $model->data['col'])) &&
  ($dbc = new Database($model->db)) &&
  ($table = $dbc->tableFromItem($cols[0])) &&
  ($cfg = $dbc->modelize($table)) &&
  isset($cfg['keys']['PRIMARY']) &&
  (count($cfg['keys']['PRIMARY']['columns']) === 1)
){
  //X::ddump($cfg);
  $hist = [];
  $fix_value = function($val, $column) use($cfg, $model){
    if (isset($cfg['keys'][$column]) && !empty($cfg['keys'][$column]['ref_column'])) {
      if ( $cfg['keys'][$column]['ref_table'] === 'bbn_options' ){
        $val = $model->inc->options->text($val);
      }
      elseif (Str::isJson($val)) {

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
    $column = $model->inc->options->code($col);
    if ($tmp['opr'] === 'DELETE') {
      $fc = $model->db->cfn($column, $table);
      $hist[] = [
        'column' => ucwords(str_replace('_', ' ', $column)),
        'before' => json_encode($model->db->select([
          'table' => $table,
          'fields' => [],
          'join' => [[
            'table' => 'bbn_history_uids',
            'on' => [
              'conditions' => [[
                'field' => 'bbn_history_uids.bbn_uid',
                'exp' => $fc
              ]]
            ]
          ]],
          'where' => [
            $fc => $model->data['uid']
          ]
        ])),
        'after' => ''
      ];
    }
    else {
      $new = \bbn\Appui\History::getValBack($table, $tmp['uid'], $tmp['tst'] + 1, $column);
      $hist[] = [
        'column' => ucwords(str_replace('_', ' ', $column)),
        'before' => $fix_value($tmp['ref'] ?? $tmp['val'], $column),
        'after' => $fix_value($new, $column)
      ];
    }
  }
  if (!empty($hist)) {
    return [
      'root' => APPUI_HISTORY_ROOT,
      'uid' => $model->data['uid'],
      'operation' => $tmp['opr'],
      'option' => $cfg['option'],
      'table' => $table,
      'id_user' => $model->data['usr'],
      'items' => $hist
    ];
  }
}
