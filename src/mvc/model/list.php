<?php
if ( isset($model->data['limit']) ){
  $liens = $model->inc->outils->get_liens_types();
  $count = <<<MYSQL
SELECT COUNT(*)
FROM bbn_history
	JOIN bbn_history_uids
  	ON bbn_history_uids.uid = bbn_history.line
  JOIN bbn_options AS bbn_tables
  	ON bbn_tables.id = bbn_history_uids.id_table
  JOIN bbn_options AS bbn_columns
  	ON bbn_columns.id = bbn_history.`column`
MYSQL;

  $query = <<<MYSQL
SELECT bbn_tables.text AS `table`, bbn_columns.text AS `col`,
dt as `date`, chrono, bbn_history.id_user, operation
FROM bbn_history
	JOIN bbn_history_uids
  	ON bbn_history_uids.uid = bbn_history.line
  JOIN bbn_options AS bbn_tables
  	ON bbn_tables.id = bbn_history_uids.id_table
  JOIN bbn_options AS bbn_columns
  	ON bbn_columns.id = bbn_history.`column`
MYSQL;

  $table = 'bbn_history';
  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'query' => $query,
    'count' => $count,
    'table' => 'bbn_history',
    'fields' => [
      'table' => 'bbn_tables.text',
      'col' => 'bbn_columns.text',
      'chrono' => 'bbn_history.chrono',
      'operation' => 'bbn_history.operation',
      'id_user' => 'bbn_history.id_user'
    ]
  ]);
  if ( $grid->check() ){
    return $grid->get_datatable();
  }
}
else{
  $dbc = new \bbn\appui\databases($model->db);
  $structure = $dbc->modelize();
  $tables = [];
  $columns = [];
  foreach ( $structure as $table => $cfg ){
    $tables[] = ['value' => $cfg['id_option'], 'text' => $table];
    foreach ( $cfg['fields'] as $col => $field ){
      $columns[] = ['value' => $field['id_option'], 'text' => $col];
    }
  }
  return [
    'root' => APPUI_HISTORY_ROOT,
    'tables' => $tables,
    'columns' => $columns
  ];
}