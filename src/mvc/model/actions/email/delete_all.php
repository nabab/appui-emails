<?php
$success = false;
$mailings = new \bbn\appui\mailings($model->db);
//delete all ready emails
$emails = $model->db->rselect_all([
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
  'where' => [
    'conditions' => [[
      'field' => 'status',
      'value' => 'ready'
    ]]
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

$user = new \bbn\user($model->db);
$n = null;
if ( !empty($emails) && !empty($model->data['id_user']) && $user->is_admin($model->data['id_user']) ){
  $n = 0;
  foreach($emails as $e){
    if ( $mailings->delete_email($e['id'])){
      $n++;
    }
  }
  $success = true;
}
return [
  'success' => $success,
  'num' => $n
];