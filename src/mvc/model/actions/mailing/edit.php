<?php
/**
 * Created by BBN Solutions.
 * User: Thomas Nabet
 * Date: 20/03/2018
 * Time: 15:39
 *
 */

/* @var $model bbn\mvc\model */

if (
    (!empty($model->data['recipients']) || empty($model->data['sent']) ) &&
    isset($model->data['content'], $model->data['title'], $model->data['sender'])
){
  if ( empty($model->data['sent']) || !\bbn\date::validateSQL($model->data['sent']) ){
    $model->data['sent'] = null;
  }
  $mailings = new \bbn\appui\mailings($model->db);
  $orig = [];
  $to_insert = false;
  $to_update = false;
  $to_delete = false;
  if ( !empty($model->data['id']) ){
    $orig = $mailings->get_mailing($model->data['id']);
    if ( !$orig['sent'] && $model->data['sent'] ){
      $to_insert = true;
    }
    else if ( $orig['sent'] && !$model->data['sent'] ){
      $to_delete = true;
    }
    else if ( $orig['sent'] && ($orig['sent'] !== $model->data['sent']) ){
      $to_update = true;
    }
  }
  else if ( !empty($model->data['sent']) ){
    $to_insert = true;
  }
  $attachments = [];
  if ( !empty($model->data['attachments']) ){
    foreach ( $model->data['attachments'] as $f ){
      if ( is_file($temp_path.$f['name']) ){
        // Add media
        $attachments[] = $temp_path.$f['name'];
      }
    }
  }
  if ( empty($model->data['id']) ){
    $model->data['id'] = $mailings->add([
      'content' => $model->data['content'],
      'title' => $model->data['title'],
      'sender' => $model->data['sender'],
      'recipients' => $model->data['recipients'],
      'sent' => $model->data['sent'],
      'attachments' => $attachments
    ]);
  }
  if ( !empty($model->data['id']) ){
    if ( $to_insert ){

    }
    else if ( $to_delete ){
      $num = $ctrl->db->delete('bbn_emails', ['id_mailing' => $model->data['id']]);
    }
    return [
      'success' => true,
      'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count')
    ];
  }
}
return $model->data['res'] ?? [];
