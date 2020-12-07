<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/
if (!$model->has_data('id_folder', true)) {
  $model->data['id_folder'] = 'inbox';
}

$em = new bbn\user\emails($model->db);
return $em->get_list($model->data['id_folder'], $model->data);