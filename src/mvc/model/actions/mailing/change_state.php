<?php

if ( !empty($model->data['id']) && ( $state = $model->data['state']) ){
  $mailings = new \bbn\appui\mailings($model->db);
  $success = false;
  //give to the mailing the state 'suspended'

  if ( $success = $mailings->change_state($model->data['id'], $state) ){
    if ( $state === 'suspended' ){
      //deletes from bbn_emails all emails ready
      $deleted = $model->db->delete('bbn_emails', [
        'id_mailing' => $model->data['id'], 
        'status' => 'ready'
      ]);
    }
  }
  
  return [
    'success' => $success,
		'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count'),
  ];
}
