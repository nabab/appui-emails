<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

 if ( !empty($model->data['data']) && !empty($model->data['data']['id']) ){
   $grid = new \bbn\appui\grid($model->db, $model->data, [
     'table' => 'bbn_emails',
     'fields' => ['email', 'status', 'delivery'],
     'filters' => [
       'conditions' => [[
         'field' => 'id_mailing',
         'value' => $model->data['data']['id']
       ]]
     ]
   ]);
   if ( $grid->check() ){
     return $grid->get_datatable();
   }
 }
