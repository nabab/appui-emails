<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

$cfg = [
  'tables' => ['bbn_emailings'],
  'fields' => [
    'id',
    'moment' => 'sent',
    'recipients' => 'recipients',
    'title' => 'bbn_notes_versions.title'
  ],
  'join' => [[
    'table' => 'bbn_notes_versions',
    'on' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'bbn_emailings.id_note',
        'operator' => '=',
        'exp' => 'bbn_notes_versions.id_note'
      ], [
        'field' => 'bbn_emailings.version',
        'operator' => '=',
        'exp' => 'bbn_notes_versions.version'
      ]]
    ]
  ]],
  'where' => [
    'logic' => 'AND',
    'conditions' => [[
      'field' => 'state',
      'operator' => '=',
      'value' => 'sending'
    ]]
  ]
];

$current = $model->db->rselect($cfg) ?: [];
$current['sent'] = !empty($current['id']) ? $model->db->count('bbn_emails', [
  'id_mailing' => $current['id'],
  'state' => 'success'
]) : 0;

$cfg['where']['conditions'] = [[
  'field' => 'state',
  'operator' => '=',
  'value' => 'ready'
], [
  'field' => 'sent',
  'operator' => 'isnotnull'
]];
$cfg['order'] = [[
  'field' => 'sent',
  'dir' => 'ASC'
]];
$next = $model->db->rselect($cfg) ?: [];

return [
	'success' => true,
  'data' => [
    'current' => $current,
    'next' => $next
  ],
  'count' => !empty($model->data['updateCount']) ? $model->get_model(APPUI_EMAILS_ROOT.'data/count') : false
];
