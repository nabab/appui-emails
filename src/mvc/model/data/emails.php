<?php
/** @var \bbn\mvc\model $model */
if ( isset($model->data['limit']) ){
  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'table' => 'bbn_emails',
    'fields' => [
      'bbn_emails.id',
      'bbn_emails.email',
      'bbn_emails.id_mailing',
      'bbn_emails.titre',
      'bbn_emails.cfg',
      'bbn_emails.etat',
      'bbn_emails.envoi',
      'bbn_emails.lu'
    ],
    'join' => [[
      'table' => 'bbn_emailings',
      'type' => 'left',
      'on' => [[
        'field' => 'bbn_emails.id_mailing',
        'exp' => 'bbn_emailings.id'
      ]]
    ]]
  ]);
  if ( $grid->check() ){
    
    return $grid->get_datatable();
  }
}