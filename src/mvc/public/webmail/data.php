<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller 
 *
 */
if (isset($ctrl->post['data'], $ctrl->post['data']['id_folder'])) {
  $ctrl->add_data(['id_folder' => $ctrl->post['data']['id_folder']])
    ->action();
}