<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 15:59
 *
 * @var $ctrl \bbn\mvc\controller
 */

if ( !empty($ctrl->arguments) &&  ($ctrl->arguments[0] !== 'home') ){
  $ctrl->set_url(APPUI_EMAILS_ROOT . 'page/home/' . $ctrl->arguments[0]);
}

$ctrl
  ->combo(_("Mailings"), true);
