<?php
if ( !empty($model->data['id']) && !empty($model->data['status']) && ( ($model->data['status'] === 'ready') || ($model->data['status'] === 'cancelled')) ){
  $mailings = new \bbn\appui\mailings($model->db);
  $success = false;
  
  $success = $mailings->delete_email($model->data['id']); 
  
  return [
    'success' => $success
  ];
}