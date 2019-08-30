<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 15:39
 *
 * @var $model \bbn\mvc\model
 */

if ( empty($model->data['destinataires']) && !empty($model->data['envoi']) ){
  return false;
}
if ( isset($model->data['content'], $model->data['title']) ){
  $notes = new \bbn\appui\notes($model->db);
  if ( empty($model->data['envoi']) || !\bbn\date::validateSQL($model->data['envoi']) ){
    $model->data['envoi'] = null;
  }
  if ( ($id_note = $notes->insert(
    $model->data['title'],
    $model->data['content'],
    $model->inc->options->from_code('mailings','types', 'notes' , 'appui')
  )) &&
    $model->db->insert('bbn_emailings', [
      'id_note' => $id_note,
      'version' => 1,
      'destinataires' => $model->data['destinataires'],
      'envoi' => $model->data['envoi']
    ])
  ){
    $model->data['id'] = $model->db->last_id();
    if ( !empty($model->data['fichiers']) ){
      $temp_path = BBN_USER_PATH.'tmp/'.$model->data['ref'].'/';
      foreach ( $model->data['fichiers'] as $f ){
        if ( is_file($temp_path.$f['name']) ){
          // Add media
          $notes->add_media($id_note, $temp_path.$f['name']);
        }
      }
    }
    return [
      'success' => true,
      'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count')
    ];
  }
}