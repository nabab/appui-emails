<?php

if ( isset($ctrl->post['value']) ){
  $ctrl->data = $ctrl->post;
  $ctrl->data['action'] = 'select';
  $ctrl->obj = $ctrl->get_model();
}
else if ( isset($ctrl->post['title']) && isset($ctrl->post['id_note']) && ( $ctrl->arguments[0] === 'insert') ){
  $ctrl->data['action'] = $ctrl->arguments[0];
  $ctrl->action();
  /*die(var_dump($ctrl->post['type']));
  $ctrl->data = $ctrl->post;
  $ctrl->data['action'] = 'insert';
  $ctrl->obj = $ctrl->get_model()->add_data(['type' => $ctrl->post['type']]);*/
}
else if ( isset($ctrl->post['title'], $ctrl->post['id_note'],  $ctrl->post['id_type']) ) {
  $ctrl->data = $ctrl->post;
  $ctrl->data['action'] = 'update';
  $ctrl->obj = $ctrl->get_model();
}
else if ( isset($ctrl->post['id_note'], $ctrl->post['action']) &&
  ($ctrl->post['action'] === 'defaut')
){
  $ctrl->action();
}
else if ( isset($ctrl->post['id_note'], $ctrl->post['action']) && ($ctrl->post['action'] === 'copy')){
  $ctrl->data = $ctrl->post;
  $ctrl->data['action'] = 'copy';
  $ctrl->obj = $ctrl->get_model();
}
else if ( isset($ctrl->post['id_note']) ){
  $ctrl->data = $ctrl->post;
  $ctrl->data['action'] = 'delete';
  $ctrl->obj = $ctrl->get_model();
}
else{
  $ctrl->combo("Lettres Types", true);
}