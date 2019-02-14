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
  $notes = new \bbn\appui\notes($model->db);
  $mail = [
    'id_mailing' => $model->data['id'],
    'titre' => '',
    'texte' => '',
    'cfg' => NULL,
    'etat' => 'pret'
  ];
  if ( 
    ($ids = $model->db->rselect('bbn_emailings', ['id_note', 'version'], ['id' => $model->data['id']])) &&
    ($note = $notes->get_full($ids['id_note'], $ids['version'])) && 
    !empty($note['title']) && 
    !empty($note['content'])
  ){
    $mail['titre'] = $note['title'];
    $mail['texte'] = $note['content'];
    if ( !empty($note['medias']) ){
      $mail['cfg']['attachments'] = [];
      foreach ( $note['medias'] as $media ){
        $mail['cfg']['attachments'][$media['name']] = $media['file'];
      }
      $mail['cfg'] = json_encode($mail['cfg']);
    }
    $add = function($id_user) use($model, $mail){
      if ( ($email = $model->db->select_one('bbn_users', 'email', ['id' => $id_user])) ){
        $model->db->insert('bbn_emails', array_merge(['email' => $email], $mail));
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
}
return ['success' => false];