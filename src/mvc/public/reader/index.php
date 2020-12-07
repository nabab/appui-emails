<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller 
 *
 */
if ($ctrl->has_arguments()) {
  if ($model = $ctrl->get_model(['id' => $ctrl->arguments[0]])) {
    echo $ctrl->get_view($model);
  }
}