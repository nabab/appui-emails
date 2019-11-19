<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 18:00
 *
 * @var $model \bbn\mvc\model
 */

if ($model->check_action(['title', 'id', 'content', 'ref', 'sender'], true)) {
  $attachments = [];
  if ( \bbn\x::has_props($model->data, ['attachments', 'ref'], true) ){
    $temp_path = $model->user_tmp_path().$model->data['ref'].'/';
    foreach ( $model->data['attachments'] as $f ){
      $attachments[] = is_file($temp_path.$f['name']) ? $temp_path.$f['name'] : $f;
    }
  }
  $model->data['attachments'] = $attachments;
  $mailings = new \bbn\appui\mailings($model->db);
  $model->data['res']['success'] = $mailings->edit($model->data['id'], $model->data);
  return $model->data['res'];
}