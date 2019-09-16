<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

$states = ['ready', 'sending', 'sent', 'suspended', 'error'. 'draft'];
$res = [];
foreach ( $states as $s ){
  $res[$s] = $model->db->select_one([
    'table' => 'bbn_emailings',
    'fields' => ['COUNT(id)'],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'state',
        'operator' => '=',
        'value' => $s === 'draft' ? 'ready' : $s
      ], [
        'field' => 'sent',
        'operator' => $s === 'draft' ? 'isnull' : 'isnotnull'
      ]]
    ]
  ]);
}
return $res;
