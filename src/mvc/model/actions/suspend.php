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
  $model->db->update('bbn_emailings', ['statut' => 'suspendu'], ['id' => $model->data['id']])
){
  return [
    'success' => true
  ];
}