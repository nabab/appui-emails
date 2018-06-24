<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/06/2018
 * Time: 17:32
 *
 * @var $model \bbn\mvc\model
 */

if ( !empty($model->data['id']) &&
  \bbn\str::is_uid($model->data['id']) &&
  !empty($model->data['users'])
){
  $add = function($id_usr) use ($model){
    if ( $email = $model->db->select_one('bbn_users', 'email', ['id' => $id_usr]) ){
      $model->db->insert('bbn_emails', [
        'email' => $email,
        'id_mailing' => $model->data['id'],
        'etat' => 'pret'
      ]);
    }
  };
  if ( is_array($model->data['users']) ){
    array_walk($model->data['users'], $add);
  }
  else {
    $add($model->data['users']);
  }
  return ['success' => true];
}
return ['success' => false];