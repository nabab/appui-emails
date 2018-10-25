<?php
if ( $model = $ctrl->get_plugin_model('data/emails', $ctrl->post) ){
  $ctrl->obj = $model;
}
else {
  $ctrl->action();
}