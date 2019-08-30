<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 16:28
 *
 * @var $model \bbn\mvc\model
 */

if ( !empty($model->data['id_note']) ){
  $masks = new \bbn\appui\masks($model->db);
  if ( $mask = $masks->get($model->data['id_note']) ){
    return [
      'success' => true,
      'data' => $mask
    ];
  }
}