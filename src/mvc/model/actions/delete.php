<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 18:00
 *
 * @var $model \bbn\mvc\model
 */

if ( !empty($model->data['id']) &&
  $model->db->delete("bbn_emailings", ['id' => $model->data['id']])
){
  return [
    'success' => true,
    'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count')
  ];
}