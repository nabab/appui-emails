<?php
/*
 * Describe what it does!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if (isset($ctrl->post['id_media'])) {
  $model = $ctrl->get_model($ctrl->post);
  if (!empty($model['file'])) {
    $ctrl->obj->file = $model['file'];
  }
}
if (!isset($ctrl->obj->file)) {
  $ctrl->obj->error = _("Impossible to find the requested file");
}