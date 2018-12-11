<?php
if ( isset($model->data['limit'], $model->data['start']) ){
  $query = <<<MYSQL
SELECT 
  LOWER(HEX(`bbn_history`.`uid`)) AS `uid`,
  `bbn_history`.`tst`,
  `bbn_history`.`opr`,
  LOWER(HEX(`bbn_history`.`usr`)) AS `usr`,
  `bbn_history`.`dt`,
  LOWER(HEX(`table_option`.`id`)) AS `tab_id`,
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
MYSQL;

  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'table' => 'bbn_history',
    'query' => $query,
    'count' => "SELECT COUNT(DISTINCT `opr`, `uid`, `tst`, `usr`) FROM `bbn_history`"
  ]);
  if ( $grid->check() ){
    return $grid->get_datatable();
  }
}