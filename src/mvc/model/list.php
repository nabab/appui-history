<?php
/** @var $model \bbn\mvc\model */

if ( isset($model->data['limit'], $model->data['start']) ){
  $count = <<<MYSQL
SELECT COUNT(DISTINCT bbn_history.uid, bbn_history.col, bbn_history.tst)
FROM bbn_history
	JOIN bbn_history_uids
  	ON bbn_history_uids.bbn_uid = bbn_history.uid
  JOIN bbn_options AS column_option
    ON column_option.id = bbn_history.col
  JOIN bbn_options AS parent_option
    ON parent_option.id = column_option.id_parent
  JOIN bbn_options AS table_option
    ON table_option.id = parent_option.id_parent
  JOIN bbn_users
    ON bbn_history.usr = bbn_users.id
  LEFT JOIN apst_adherents
    ON apst_adherents.uid = bbn_history.uid
MYSQL;

  $query = <<<MYSQL
SELECT bbn_history.uid, bbn_history.tst, bbn_history.opr, bbn_history.col, bbn_history.usr, bbn_history.dt,
  table_option.id AS tab_id, table_option.text AS tab_name, column_option.id AS col_id, column_option.text AS col_name,
  apst_adherents.nom AS adh, apst_adherents.id AS id_adh, apst_adherents.statut AS adh_statut
FROM bbn_history
	JOIN bbn_history_uids
  	ON bbn_history_uids.bbn_uid = bbn_history.uid
  JOIN bbn_options AS column_option
    ON column_option.id = bbn_history.col
  JOIN bbn_options AS parent_option
    ON parent_option.id = column_option.id_parent
  JOIN bbn_options AS table_option
    ON table_option.id = parent_option.id_parent
  JOIN bbn_users
    ON bbn_history.usr = bbn_users.id
  LEFT JOIN apst_adherents
    ON apst_adherents.uid = bbn_history.uid
MYSQL;

  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'query' => $query,
    'count' => $count,
    'table' => 'bbn_history'
  ]);
  if ( $grid->check() ){
    $res = $grid->get_datatable();
    if ( count($res['data']) ){
      $type_liens = $model->inc->options->options('LIENS');
      $structures = [];
      $adherents = [];
      $tiers = new \apst\tiers($model->db);
      $lieux = new \apst\lieux($model->db);
      foreach ( $res['data'] as $i => $r ){
        if ( !isset($structures[$r['tab_name']]) ){
          $structures[$r['tab_name']] = $model->db->modelize($r['tab_name']);
        }
        if ( $r['tab_name'] === 'apst_liens' ){
          $res['data'][$i]['tab_name'] .= ' ('.$type_liens[$model->db->select_one("apst_liens", "type_lien", ['id' => $r['uid']])].')';
        }
        if ( !empty($r['adh']) &&
          !empty($r['id_adh']) &&
          !isset($adherents[$r['id_adh']])
        ){
          $adherents[$r['id_adh']] = ['nom' => $r['adh']];
        }
        if ( empty($r['id_adh']) &&
          isset($structures[$r['tab_name']]['fields']['id_adherent']) &&
          ($res['data'][$i]['id_adh'] = $model->db->get_one("
            SELECT id_adherent
            FROM $r[tab_name]
            WHERE id = ?",
            hex2bin($r['uid'])
          ))
        ){
          if ( !isset($adherents[$res['data'][$i]['id_adh']]) ){
            $adherents[$res['data'][$i]['id_adh']] = $model->db->rselect('apst_adherents', ['nom', 'statut'], [
              'id' => $res['data'][$i]['id_adh']
            ]);
          }
          $res['data'][$i]['adh'] = $adherents[$res['data'][$i]['id_adh']]['nom'];
          $res['data'][$i]['adh_statut'] = $adherents[$res['data'][$i]['id_adh']]['statut'];
        }
        else if ( $r['tab_name'] === 'apst_adherents' ){
          if ( !isset($adherents[$res['data'][$i]['id_adh']]) ){
            $adherents[$res['data'][$i]['id_adh']] = $model->db->rselect('apst_adherents', ['nom', 'statut'], ['id' => $res['data'][$i]['id_adh']]);
          }
          $res['data'][$i]['adh'] = $adherents[$res['data'][$i]['id_adh']]['nom'];
          $res['data'][$i]['adh_statut'] = $adherents[$res['data'][$i]['id_adh']]['statut'];
        }
        else if ( $r['tab_name'] === 'bbn_people' ){
          $res['data'][$i]['adh'] = $tiers->fnom($r['uid']);
        }
        else if ( $r['tab_name'] === 'bbn_addresses' ){
          $res['data'][$i]['adh'] = $lieux->fadresse($r['uid']);
        }
      }
    }
    return $res;
  }
}
else {
  $res = [
    'root' => APPUI_HISTORY_ROOT,
    'tables' => [],
    'columns' => []
  ];
  $dbc = new \bbn\appui\databases($model->db);
  foreach ( $dbc->tables() as $i => $table ){
    $res['tables'][] = ['value' => $table['id'], 'text' => $table['text']];
    foreach ( $dbc->columns($table['id']) as $id => $col ){
      $res['columns'][] = ['value' => $id, 'text' => $table['text'].'.'.$col];
    }
  }
  return $res;
}
