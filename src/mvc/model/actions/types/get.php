<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 16:28
 *
 * @var $model \bbn\mvc\model
 */

if ( !empty($model->data['id_note']) && !empty($model->data['version']) ){
  $notes = new \bbn\appui\notes($model->db);
  if ( $note = $notes->get($model->data['id_note'], $model->data['version']) ){
    return [
      'success' => true,
      'data' => $note
    ];
  }
}