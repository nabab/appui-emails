<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

 $model->data['data']['success'] = false;
 $query = 'SELECT bbn_emails.* FROM bbn_emails';
 $filters = ['id_mailing' => hex2bin($model->data['data']['id'])];


 if ( !empty($model->data['data']) && !empty($model->data['data']['id']) ){

   $grid = new \bbn\appui\grid($model->db, $model->data, [
     'table' => 'bbn_emails',
     'query'=> $query,
     'group_by' => 'bbn_emails.id',
     'filters'  => $filters
   ]);
   $model->data['data']['success'] = true;
   return $grid->get_datatable();
 }
