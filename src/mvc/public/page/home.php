<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 15:59
 *
 * @var $ctrl \bbn\mvc\controller
 */


//if the page home set the url the tabnav will be doubled or tripled!!  the forcing you see when you reload the page is only on the selectedNode of the tree
if ( !empty($ctrl->arguments) &&  ($ctrl->arguments[0] !== 'home') ){
  
  //$ctrl->obj->url = APPUI_EMAILS_ROOT . 'page/home/' . $ctrl->arguments[0];
  //$ctrl->set_url(APPUI_EMAILS_ROOT . 'page/home/' . $ctrl->arguments[0]);
  $ctrl->obj->url = APPUI_EMAILS_ROOT . 'page/home/';
}
else{
  $ctrl->set_url(APPUI_EMAILS_ROOT . 'page/home/all');
}
$ctrl
  ->combo(_("Mailings"), true);