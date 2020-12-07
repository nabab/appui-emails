<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/
if ($model->has_data('id', true)) {
	$em = new bbn\user\emails($model->db);
  return $em->get_email($model->data['id']);
}