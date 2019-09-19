<?php
$success = 0;
//case of cancelling a single row
if ( !empty($model->data['id']) && !empty($model->data['status']) && ($model->data['status'] === 'cancelled') ){
  $mailings = new \bbn\appui\mailings($model->db);
  $success = $mailings->change_email_status($model->data['id'], 'ready'); 
  return ['success' => $success];
}