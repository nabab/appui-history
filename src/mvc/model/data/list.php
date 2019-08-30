<?php
if ( isset($model->data['limit'], $model->data['start']) ){
  $query = <<<MYSQL
SELECT * FROM (
  SELECT 
    `bbn_history`.`uid` AS `uid`,
    `bbn_history`.`tst`,
    `bbn_history`.`opr`,
    `bbn_history`.`usr` AS `usr`,
    DATE_FORMAT(`bbn_history`.`dt`, '%Y-%m-%d %H:%i') AS `dt`,
    `table_option`.`id` AS `tab_id`,
    `table_option`.`text` AS `tab_name`,
    GROUP_CONCAT(LOWER(HEX(`bbn_history`.`col`)) SEPARATOR ',') AS `col_id`,
    GROUP_CONCAT(`column_option`.`text` SEPARATOR ',') AS `col_name`
  FROM `bbn_history`
    JOIN `bbn_history_uids`
      ON `bbn_history_uids`.`bbn_uid` = `bbn_history`.`uid`
    JOIN `bbn_options` AS `column_option`
      ON `column_option`.`id` = `bbn_history`.`col`
    JOIN `bbn_options` AS `parent_option`
      ON `parent_option`.`id` = `column_option`.`id_parent`
    JOIN `bbn_options` AS `table_option`
      ON `table_option`.`id` = `parent_option`.`id_parent`
    JOIN `bbn_users`
      ON `bbn_history`.`usr` = `bbn_users`.`id`
  GROUP BY `bbn_history`.`opr`, `bbn_history`.`uid`, `bbn_history`.`tst`, `bbn_history`.`usr`
) AS h
MYSQL;

  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'tables' => [
      'h' => 'bbn_history',
      'bbn_history'
    ],
    'fields' => [
      'h.usr'=> 'bbn_history.usr',
      'h.opr'=> 'bbn_history.opr'
    ],
    'query' => $query,
    'num' => 1
  ]);
  $cfg = $grid->get_cfg();
  $num = $model->db->get_one("
    SELECT COUNT(*) FROM (".
      $query.PHP_EOL.
      $model->db->get_where($cfg).
    ") AS mytot",
    !empty($cfg['values']) ? array_map(function($v){
      if ( \bbn\str::is_uid($v) ){
        return hex2bin($v);
      }
      return $v;
    }, $cfg['values']) : []
  );
  if ( $grid->check() ){
    $ret = [
      'data' => $grid->get_data(),
      'total' => $num,
      'success' => true,
      'error' => false
    ];
    if ( isset($model->data['start']) ){
      $ret['start'] = $model->data['start'];
    }
    if ( isset($model->data['limit']) ){
      $ret['limit'] = $model->data['limit'];
    }
    return $ret;
  }
}