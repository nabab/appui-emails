<?php
if ( $model = $ctrl->get_plugin_model('data/sent', $ctrl->post) ){
  $ctrl->obj = $model;
}
else {
  $ctrl->action();
}