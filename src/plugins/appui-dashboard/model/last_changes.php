<?php
$limit = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
return [
  'insert' => $model->db->rselectAll([
    'table' => 'bbn_history',
    'fields' => [
      'bbn_history.uid',
      'bbn_history.col',
      'bbn_history.usr',
      'bbn_history.dt',
      'bbn_history.tst',
      'column' => 'c.text',
      'table' => 't.text'
    ],
    'join' => [[
      'table' => 'bbn_options',
      'alias' => 'c',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_history.col',
          'exp' => 'c.id'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 'cols',
      'on' => [
        'conditions' => [[
          'field' => 'cols.id',
          'exp' => 'c.id_parent'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 't',
      'on' => [
        'conditions' => [[
          'field' => 't.id',
          'exp' => 'cols.id_parent'
        ]]
      ]
    ]],
    'where' => [
      'conditions' => [[
        'field' => 'bbn_history.opr',
        'value' => 'INSERT'
      ]]
    ],
    'order' => [[
      'field' => 'bbn_history.tst',
      'dir' => 'DESC'
    ]],
    'limit' => $limit
  ]),
  'update' => $model->db->rselectAll([
    'table' => 'bbn_history',
    'fields' => [
      'bbn_history.uid',
      'bbn_history.col',
      'bbn_history.usr',
      'bbn_history.dt',
      'bbn_history.tst',
      'bbn_history.val',
      'bbn_history.ref',
      'column' => 'c.text',
      'table' => 't.text',
    ],
    'join' => [[
      'table' => 'bbn_options',
      'alias' => 'c',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_history.col',
          'exp' => 'c.id'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 'cols',
      'on' => [
        'conditions' => [[
          'field' => 'cols.id',
          'exp' => 'c.id_parent'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 't',
      'on' => [
        'conditions' => [[
          'field' => 't.id',
          'exp' => 'cols.id_parent'
        ]]
      ]
    ]],
    'where' => [
      'conditions' => [[
        'field' => 'bbn_history.opr',
        'value' => 'UPDATE'
      ]]
    ],
    'order' => [[
      'field' => 'bbn_history.tst',
      'dir' => 'DESC'
    ]],
    'limit' => $limit
  ]),
  'delete' => $model->db->rselectAll([
    'table' => 'bbn_history',
    'fields' => [
      'bbn_history.uid',
      'bbn_history.col',
      'bbn_history.usr',
      'bbn_history.dt',
      'bbn_history.tst',
      'bbn_history.val',
      'bbn_history.ref',
      'column' => 'c.text',
      'table' => 't.text',
    ],
    'join' => [[
      'table' => 'bbn_options',
      'alias' => 'c',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_history.col',
          'exp' => 'c.id'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 'cols',
      'on' => [
        'conditions' => [[
          'field' => 'cols.id',
          'exp' => 'c.id_parent'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 't',
      'on' => [
        'conditions' => [[
          'field' => 't.id',
          'exp' => 'cols.id_parent'
        ]]
      ]
    ]],
    'where' => [
      'conditions' => [[
        'field' => 'bbn_history.opr',
        'value' => 'DELETE'
      ]]
    ],
    'order' => [[
      'field' => 'bbn_history.tst',
      'dir' => 'DESC'
    ]],
    'limit' => $limit
  ])
];