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
      'bbn_emails.read',
      'bbn_emails.priority',
      'bbn_emailings.id_note'
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
    $note = new \bbn\appui\notes($model->db);
    $tmp_grid = $grid->get_datatable();
    $tmp_grid['data'] = array_map(function($a)use($note){
      if(!empty($a['id_note'])){
        $a['attachments'] = $note->get_medias($a['id_note']);
      }
      return $a;
    }, $tmp_grid['data']);
    return $tmp_grid;
  }
}