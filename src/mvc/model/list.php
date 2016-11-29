<?php
if ( isset($model->data['filter'], $model->data['filter']['filters']) ){
  foreach ( $model->data['filter']['filters'] as $i => $f ){
    if ( $f['field'] === 'tab' ){
      $table = $model->db->table_full_name($f['value']);
      $model->data['filter']['filters'][$i] = [
        'field' => 'column',
        'operator' => 'startswith',
        'value' => $model->db->table_full_name($f['value']).'.'
      ];
    }
    if ( $f['field'] === 'col' ){
      $table = $model->db->table_full_name($f['value']);
      $model->data['filter']['filters'][$i] = [
        'field' => 'column',
        'operator' => 'endswith',
        'value' => '.'.$model->db->col_simple_name($f['value'])
      ];
    }
  }
}
$grid = new \bbn\appui\grid($model->db, $model->data, ['chrono', 'operation', 'column', 'id_user']);

if ( isset($model->data['take']) && $grid->check() ){

  $where = $grid->where();
  if ( !empty($where) ){
    $where = " AND $where ";
  }
  $sort = $grid->order();
  if ( !empty($sort) ){
    $sort = " ORDER BY $sort ";
  }
  else{
    $sort = " ORDER BY chrono DESC ";
  }
  $type_liens = $model->inc->options->options(33);
  $structures = [];
  $adherents = [];
  $tiers = new \apst\tiers($model->db);
  $lieux = new \apst\lieux($model->db);
  $rows = $model->db->get_rows("
    SELECT line, chrono, `operation`, bbn_history.id AS history_id, id_user,
    SUBSTRING(`column`, 10) AS path,
    SUBSTRING(SUBSTRING(`column`, 10), 1, POSITION('.' IN SUBSTRING(`column`, 10))-1 ) AS tab,
    SUBSTRING(SUBSTRING(`column`, 10), POSITION('.' IN SUBSTRING(`column`, 10))+1 ) AS col
    FROM bbn_history
      LEFT JOIN apst_users
        ON apst_users.id = id_user
    WHERE 1 $where
    $sort
    LIMIT {$grid->start()}, {$grid->limit()}");
  foreach ( $rows as $i => $r ){
    $rows[$i]['adh'] = '-';
    $rows[$i]['id_adh'] = null;
    if ( !isset($structures[$r['tab']]) ){
      $structures[$r['tab']] = $model->db->modelize($r['tab']);
    }
    if ( $r['tab'] === 'apst_liens' ){
      $rows[$i]['tab'] .= ' ('.$type_liens[$model->db->select_one("apst_liens", "type_lien", ['id' => $r['line']])].')';
    }
    if ( isset($structures[$r['tab']]['fields']['id_adherent']) &&
        ($rows[$i]['id_adh'] = $model->db->select_one($r['tab'], 'id_adherent', [
          'id' => $r['line'],
          'actif' => $r['operation'] === 'DELETE' ? 0 : 1
        ])) ){
      if ( !isset($adherents[$rows[$i]['id_adh']]) ){
        $adherents[$rows[$i]['id_adh']] = $model->db->select_one('apst_adherents', 'nom', [
          'id' => $rows[$i]['id_adh']
        ]);
      }
      $rows[$i]['adh'] = $adherents[$rows[$i]['id_adh']];
    }
    else if ( $r['tab'] === 'apst_adherents' ){
      $rows[$i]['id_adh'] = $r['line'];
      if ( !isset($adherents[$rows[$i]['id_adh']]) ){
        $adherents[$rows[$i]['id_adh']] = $model->db->select_one('apst_adherents', 'nom', ['id' => $rows[$i]['id_adh']]);
      }
      $rows[$i]['adh'] = $adherents[$rows[$i]['id_adh']];
    }
    else if ( $r['tab'] === 'apst_tiers' ){
      $rows[$i]['adh'] = $tiers->fnom($r['line']);
    }
    else if ( $r['tab'] === 'apst_lieux' ){
      $rows[$i]['adh'] = $lieux->fadresse($r['line']);
    }
  }
  return [
    'data' => $rows,
    'total' => $model->db->get_one("
      SELECT COUNT(DISTINCT line, chrono, id_user, operation)
      FROM bbn_history
      WHERE 1 $where")
  ];
}