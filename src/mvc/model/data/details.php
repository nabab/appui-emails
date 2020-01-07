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
    'fields' => [
      'id',
      'email',
      'status', 
      'delivery', 
      'priority', 
      'id_note' => 'bbn_notes_versions.id_note'
    ],
    
    'join' => [[
      'table' => 'bbn_emailings',
      'type' => 'left',
      'on' => [[
        'field' => 'bbn_emails.id_mailing',
        'exp' => 'bbn_emailings.id'
      ]]
    ], [
      'table' => 'bbn_notes_versions',
      'type' => 'left',
      'on' => [[
        'field' => 'bbn_emailings.id_note',
        'exp' => 'bbn_notes_versions.id_note'
      ], [
        'field' => 'bbn_emailings.version',
        'exp' => 'bbn_notes_versions.version'
      ]]
    ]],
    'filters' => [
      'conditions' => [[
        'field' => 'id_mailing',
        'value' => $model->data['data']['id']
      ]]
    ]
  ]);
  if ( $grid->check() ){
    $note = new \bbn\appui\notes($model->db);
    $tmp_grid = $grid->get_datatable();
    $tmp_grid['data'] = array_map(function($a)use($note){
      if ( !empty($a['id_note']) ){
        $a['attachments'] = $note->get_medias($a['id_note']);
        return $a;
      }
      return $a;
    }, $tmp_grid['data']);
    return $tmp_grid;
  }
}
