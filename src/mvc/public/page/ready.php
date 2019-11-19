<?php
/*
$ctrl->set_icon('nf nf-fa-envelope_open_text');
$d = $ctrl->get_plugin_model('page/emails', $ctrl->data);

if ( is_null($d) ){
  $d = $ctrl->get_model($ctrl->data);

}
$ctrl->set_title($model['title'] ?? _("e-Mails ready"));
$views = $ctrl->get_plugin_views('page/emails', $d);
$ctrl->obj->data = $d;
echo $views['html'] ?: $ctrl->get_view();
$ctrl->add_script($views['js'] ?: $ctrl->get_view('', 'js'));
$ctrl->obj->css = $views['css'] ?: $ctrl->get_less();*/

$ctrl->obj->url = APPUI_EMAILS_ROOT.'page/ready';
$ctrl
  ->set_url(APPUI_EMAILS_ROOT.'page/ready')
  ->set_icon('nf nf-fa-envelope_o')
  ->combo(_("e-Mails ready"));