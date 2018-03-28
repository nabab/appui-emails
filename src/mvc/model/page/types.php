<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 14:39
 *
 * @var $model \bbn\mvc\model
 */

$notes = new \bbn\appui\masks($model->db);
$masks = array_map(function($a){
  $a['content'] = '';
  return $a;
}, $notes->get_all());
return [
  'root' => APPUI_EMAILS_ROOT,
  'is_dev' => $model->inc->user->is_dev(),
  'categories' => $masks
];