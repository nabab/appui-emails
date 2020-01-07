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
    if($last_creator = $model->db->select_one([
      'table' => 'bbn_notes_versions',
      'fields' => ['id_user'],
      'where' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'id_note',
          'operator' => '=',
          'value' => $mask['id_note']
        ]]
        ],
        'order' => [[
          'field' => 'version',
          'dir' => 'DESC'
        ]]
    ])
    ){
      $mask['creator'] = $last_creator;
        return [
          'success' => true,
          'data' => $mask
        ];
      }
    }
}