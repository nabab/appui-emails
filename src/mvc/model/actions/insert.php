<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 15:39
 *
 * @var $model \bbn\mvc\model
 */

if ( empty($model->data['recipients']) && !empty($model->data['sent']) ){
  return false;
}
if ( isset($model->data['content'], $model->data['title'], $model->data['sender']) ){
  $mailings = new \bbn\appui\mailings($model->db);
  if ( empty($model->data['sent']) || !\bbn\date::validateSQL($model->data['sent']) ){
    $model->data['sent'] = null;
  }
  $attachments = [];
  if ( !empty($model->data['fichiers']) ){
    foreach ( $model->data['fichiers'] as $f ){
      if ( is_file($temp_path.$f['name']) ){
        // Add media
        $attachments[] = $temp_path.$f['name'];
      }
    }
  }
  if ( $model->data['id'] = $mailings->add([
    'content' => $model->data['content'],
    'title' => $model->data['title'],
    'sender' => $model->data['sender'],
    'recipients' => $model->data['recipients'],
    'sent' => $model->data['sent'],
    'attachments' => $attachments
  ]) ){
    return [
      'success' => true,
      'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count')
    ];
  }
}