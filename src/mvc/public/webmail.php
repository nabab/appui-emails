<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller
 *
 */
if (isset($ctrl->post['limit'])) {
  if (isset($ctrl->post['data'], $ctrl->post['data']['id_folder'])) {
	  $ctrl->add_data(['id_folder' => $ctrl->post['data']['id_folder']]);
  }
  $ctrl->action();
}
else {
  $ctrl->add_data(['root' => APPUI_EMAILS_ROOT])
    ->combo(_('Webmail'), true);
}