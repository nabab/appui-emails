<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model */
$id_accounts = $model->inc->options->from_code('accounts', 'emails', 'appui');
$id_types = $model->inc->options->from_code('types', 'emails', 'appui');

return [
  'types' => array_map(function($a){
    return [
      'value' => $a['code'],
      'text' => $a['text']
    ];
  }, $model->inc->options->full_options($id_types)),
  'data' => $model->inc->pref->get_all($id_accounts)
];