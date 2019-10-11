<?php
/** @var \bbn\mvc\model $model */

if ( isset($model->data['limit']) ){
  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'table' => 'bbn_emails',
    'fields' => [
      'bbn_emails.id',
      'bbn_emails.email',
      'bbn_emails.id_mailing',
      'subject' => 'IFNULL(bbn_emails.subject, bbn_notes_versions.title)',
      'bbn_emails.cfg',
      'bbn_emails.status',
      'bbn_emails.delivery',
      'bbn_emails.read'
    ],
    'join' => [[
      'table' => 'bbn_emailings',
      'type' => 'left',
      'on' => [[
        'field' => 'bbn_emails.id_mailing',
        'exp' => 'bbn_emailings.id'
      ]]
    ], [
      'table' => 'bbn_notes_versions',
      'type' => 'left',
      'on' => [[
        'field' => 'bbn_emailings.id_note',
        'exp' => 'bbn_notes_versions.id_note'
      ], [
        'field' => 'bbn_emailings.version',
        'exp' => 'bbn_notes_versions.version'
      ]]
    ]]
  ]);
  if ( $grid->check() ){
    return $grid->get_datatable();
  }
}