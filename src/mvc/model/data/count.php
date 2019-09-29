<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

$states = ['ready', 'sending', 'sent', 'suspended', 'error', 'draft', 'cancelled'];
$res = [];
foreach ( $states as $s ){
  $res[$s] = $model->db->select_one([
    'table' => 'bbn_emailings',
    'fields' => ['COUNT(bbn_emailings.id)'],
    'join' => [[
      'table' => 'bbn_notes',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'bbn_emailings.id_note',
          'operator' => '=',
          'exp' => 'bbn_notes.id'
        ], [
          'field' => 'bbn_notes.active',
          'operator' => '=',
          'value' => 1
        ]]
      ]]],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'state',
        'operator' => '=',
        'value' => $s === 'draft' ? 'ready' : $s
      ], [
        'field' => 'sent',
        'operator' => $s === 'draft' ? 'isnull' : 'isnotnull'
      ], [
        'field' => 'id_note',
        'operator' => 'isnotnull'
      ]]
    ]
  ]);

 
}

return $res;
