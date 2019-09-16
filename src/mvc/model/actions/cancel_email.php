<?php


$success = 0;
//case of cancelling a single row
if ( !empty($model->data['id']) && !empty($model->data['email']) && !empty($model->data['status']) ){
  $success = $model->db->update('bbn_emails', ['status' => 'cancelled'], [
    'id' => $model->data['id'],
    'email' => $model->data['email']
  ]);
}
//cancelling multiple rows
else if ( !empty($model->data['selected']) ){
  
  foreach ( $model->data['selected'] as $s ){
    if ($model->db->update('bbn_emails', ['status' => 'cancelled'], [
      'id' => $s['id'],
      'email' => $s['email']
    ])){
      $success ++;
    }
  }
}
return ['success' => $success ];