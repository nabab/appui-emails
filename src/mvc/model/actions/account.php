<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model */
if ($model->has_data('action')) {
  switch ($model->data['action'])
  {
    case 'test':
      break;
    case 'delete':
      break;
    case 'update':
      break;
    case 'insert':
      break;
  }
}
return $model->data['res'];