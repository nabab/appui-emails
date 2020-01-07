<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:02
 *
 * @var $ctrl \bbn\mvc\controller
 */
if ( !empty($ctrl->arguments[0]) && \bbn\str::is_uid($ctrl->arguments[0]) ){
  $ctrl->data = [
    'id' => $ctrl->arguments[0],
    'root' => APPUI_EMAILS_ROOT,
  ];
  if ( $model = $ctrl->get_model() ){
    if ( !empty($model['title']) && (strlen($model['title']) > 20) ){
      $model['title'] = substr($model['title'], 0, 20) . '...';
    }
    echo $ctrl
      ->set_icon('nf nf-fa-th_list')
      ->set_url(APPUI_EMAILS_ROOT.'page/details/'.$ctrl->arguments[0])
      ->set_title($model['title'] ?: _('Untitled'))
      ->add_js($ctrl->data)
      ->get_view();
  }
}
else{
  return false;
  //$ctrl->obj->url = APPUI_EMAILS_ROOT.'page/details/';
  $ctrl->combo();
}
