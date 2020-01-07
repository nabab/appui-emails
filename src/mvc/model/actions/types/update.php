<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 19:19
 *
 * @var $model \bbn\mvc\model
 */

if ( 
  !empty($model->data['id_note']) &&
  !empty($model->data['content']) &&
  !empty($model->data['title']) &&
  !empty($model->data['name'])
){
  $masks = new \bbn\appui\masks($model->db);
  if ( $masks->update([
    'id_note' => $model->data['id_note'],
    'title' => $model->data['title'],
    'content' => $model->data['content'],
    'name' => $model->data['name'],
    'default' => $model->data['default'],
    'id_user' =>  $model->data['id_user'],
  ]) ){
    return [
      'success' => true,
      'data' => $masks->get($model->data['id_note'])
    ];
  }
}