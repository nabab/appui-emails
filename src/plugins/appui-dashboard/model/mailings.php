<?php
/** @var \bbn\mvc\model $model */
$limit = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$res = [
  'ready' => $model->db->rselect_all([
    'table' => 'bbn_emailings',
    'fields' => [
      'bbn_emailings.id',
      'bbn_emailings.sent',
      'recipients' => 'rec.text',
      'sender' => 'sen.text',
      'bbn_notes_versions.title'
    ],
    'join' => [[
      'table' => 'bbn_options',
      'alias' => 'rec',
      'on' => [
        'conditions' => [[
          'field' => 'rec.id',
          'exp' => 'bbn_emailings.recipients'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 'sen',
      'on' => [
        'conditions' => [[
          'field' => 'sen.id',
          'exp' => 'bbn_emailings.sender'
        ]]
      ]
    ], [
      'table' => 'bbn_notes_versions',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_notes_versions.id_note',
          'exp' => 'bbn_emailings.id_note'
        ], [
          'field' => 'bbn_notes_versions.version',
          'exp' => 'bbn_emailings.version'
        ]]
      ]
    ]],
    'order' => [[
      'field' => 'bbn_emailings.sent',
      'dir' => 'ASC'
    ]],
    'where' => [[
      'field' => 'bbn_emailings.sent',
      'operator' => 'isnotnull'
    ], [
      'field' => 'bbn_emailings.state',
      'value' => 'ready'
    ]],
    'limit' => $limit
  ]),
  'sent' => $model->db->rselect_all([
    'table' => 'bbn_emailings',
    'fields' => [
      'bbn_emailings.id',
      'bbn_emailings.sent',
      'recipients' => 'rec.text',
      'sender' => 'sen.text',
      'bbn_notes_versions.title'
    ],
    'join' => [[
      'table' => 'bbn_options',
      'alias' => 'rec',
      'on' => [
        'conditions' => [[
          'field' => 'rec.id',
          'exp' => 'bbn_emailings.recipients'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'alias' => 'sen',
      'on' => [
        'conditions' => [[
          'field' => 'sen.id',
          'exp' => 'bbn_emailings.sender'
        ]]
      ]
    ], [
      'table' => 'bbn_notes_versions',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_notes_versions.id_note',
          'exp' => 'bbn_emailings.id_note'
        ], [
          'field' => 'bbn_notes_versions.version',
          'exp' => 'bbn_emailings.version'
        ]]
      ]
    ]],
    'order' => [[
      'field' => 'bbn_emailings.sent',
      'dir' => 'ASC'
    ]],
    'where' => [[
      'field' => 'bbn_emailings.sent',
      'operator' => 'isnotnull'
    ], [
      'field' => 'bbn_emailings.state',
      'value' => 'sent'
    ]],
    'limit' => $limit
  ])
];
foreach ( $res as $i => $r ){
  foreach ( $r as $k => $s ){
    $res[$i][$k]['total'] = $model->db->count([
      'table' => 'bbn_emails',
      'fields' => ['id'],
      'where' => [
        'conditions' => [[
          'field' => 'id_mailing',
          'value' => $s['id']
        ]]
      ]
    ]);
    $res[$i][$k]['success'] = $model->db->count([
      'table' => 'bbn_emails',
      'fields' => ['id'],
      'where' => [
        'conditions' => [[
          'field' => 'id_mailing',
          'value' => $s['id']
        ], [
          'field' => 'status',
          'value' => 'success'
        ]]
      ]
    ]);
  }
}
return $res;