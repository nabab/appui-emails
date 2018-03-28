<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 19:19
 *
 * @var $model \bbn\mvc\model
 */

if ( !empty($model->data['id_note']) &&
  !empty($model->data['content']) &&
  !empty($model->data['title'])
){
  $notes = new \bbn\appui\notes($model->db);
  $masks = new \bbn\appui\masks($model->db);
  if ( $notes->insert_version($model->data['id_note'], $model->data['title'], $model->data['content']) ){
    return [
      'success' => true,
      'data' => $masks->get($model->data['id_note'])
    ];
  }
}