<?php

if ( !empty($model->data['id']) && ( $state = $model->data['state']) ){
  $mailings = new \bbn\appui\mailings($model->db);
  $success = $mailings->change_state($model->data['id'], $state);
  return [
    'success' => $success,
		'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count'),
  ];
}