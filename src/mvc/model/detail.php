<?php
/** @var $model \bbn\mvc\model */
if ( !empty($model->data['history_id']) &&
    $hist = $model->db->rselect('bbn_history', [], ['id' => $model->data['history_id']])
){
  $tmp = explode('.', $hist['column']);
  $table = $tmp[1];
  $cfg = $model->db->modelize($table);
  if ( $cfg &&
          isset($cfg['keys']['PRIMARY']) &&
          (count($cfg['keys']['PRIMARY']['columns']) === 1) ){
    $primary = $cfg['keys']['PRIMARY']['columns'][0];
    $tiers = new \apst\tiers($model->db);
    $lieux = new \apst\lieux($model->db);
    //if ( ($cfg['fields'][$col] === 'text') && ($text = json_decode
    $current = $model->db->rselect($table, [], [$primary => $hist['line']]);
    $res = [
      'id' => $hist['line'],
      'operation' => $hist['operation'],
      'table' => $table,
      'msg' => "L'entrée suivante a été ",
      'moments' => [
        ['titre' => 'Avant'],
        ['titre' => 'Après']
      ]
    ];
    $res['moments'][0]['lines'] = [];
    $res['moments'][1]['lines'] = [];
    switch ( $hist['operation'] ){
      case 'INSERT':
      $res['msg'] .= "rajoutée";
      $res['moments'][0]['lines'] = false;
      break;

      case 'UPDATE':
      $res['msg'] .= "modifiée";
      $tmp = explode('.', $hist['column']);
      break;

      case 'DELETE':
      $res['msg'] .= "supprimée";
      $res['moments'][1]['lines'] = false;
      break;

      case 'RESTORE':
      $res['msg'] .= "restaurée";
      $res['moments'][0]['lines'] = false;
      break;

      default:
      die("Operation: ".$hist['operation']);
    }
    $res['msg'] .= " par ".$model->inc->outils->user($hist['id_user']);
    foreach ( $res['moments'] as $i => $m ){
      if ( is_array($m['lines']) ){
        if ( $i === 0 ){
          if ( $hist['operation'] === 'DELETE' ){
            $all = $model->db->rselect($table, $m['lines'], [$primary => $hist['line'], 'actif' => 0]);
          }
          else{
	          $all = \bbn\appui\history::get_row_back($table, $m['lines'], [$primary => $hist['line']], $hist['chrono']);
          }
        }
        else {
          $all = \bbn\appui\history::get_row_back($table, $m['lines'], [$primary => $hist['line']], $hist['chrono'] + 1);
        }
        if ( !empty($all) ){
          foreach ( $all as $k => $a ){
            if ( isset($cfg['keys'][$k]) && !empty($cfg['keys'][$k]['ref_column']) ){
              if ( $k === 'id_lieu' ){
                $a = $lieux->fadresse($a);
              }
              else if ( $k === 'id_tiers' ){
                $a = $a.' '.$tiers->fnom($a);
              }
              else if ( $cfg['keys'][$k]['ref_table'] === 'bbn_options' ){
                $a = $model->inc->options->titre($a);
              }
            }
            array_push($res['moments'][$i]['lines'], [
              'titre' => ucwords(str_replace('_', ' ', $k)),
              'valeur' => $a
            ]);
          }
        }
      }
    }
  }
  return $res;
}