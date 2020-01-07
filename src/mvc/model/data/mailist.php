<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model */
if (\bbn\x::has_prop($model->data, 'recipients')) {
  $mailings = new \bbn\appui\mailings($model->db);
  $model->data['res'] = $model->get_plugin_model('data/mailist', $model->data, 'emails');
  if ($model->data['res']['success'] && !empty($model->data['num'])) {
    $model->data['res']['num'] = count($model->data['res']['data']);
    unset($model->data['res']['data']);
  }
}
return $model->data['res'];