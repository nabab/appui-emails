<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

return [
  'ready' => $model->db->select_one([
    'table' => 'bbn_emailings',
    'fields' => ['COUNT(id)'],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'statut',
        'operator' => '=',
        'value' => 'pret'
      ], [
        'field' => 'envoi',
        'operator' => 'isnotnull'
      ]]
    ]
  ]),
  'in_progress' => $model->db->select_one([
    'table' => 'bbn_emailings',
    'fields' => ['COUNT(id)'],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'statut',
        'operator' => '=',
        'value' => 'en cours'
      ], [
        'field' => 'envoi',
        'operator' => 'isnotnull'
      ]]
    ]
  ]),
  'sent'  => $model->db->select_one([
    'table' => 'bbn_emailings',
    'fields' => ['COUNT(id)'],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'statut',
        'operator' => '=',
        'value' => 'envoye'
      ], [
        'field' => 'envoi',
        'operator' => 'isnotnull'
      ]]
    ]
  ]),
  'suspended' =>  $model->db->select_one([
    'table' => 'bbn_emailings',
    'fields' => ['COUNT(id)'],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'statut',
        'operator' => '=',
        'value' => 'suspendu'
      ], [
        'field' => 'envoi',
        'operator' => 'isnotnull'
      ]]
    ]
  ]),
  'draft' =>  $model->db->select_one([
    'table' => 'bbn_emailings',
    'fields' => ['COUNT(id)'],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'envoi',
        'operator' => 'isnull'
      ]]
    ]
  ])
];