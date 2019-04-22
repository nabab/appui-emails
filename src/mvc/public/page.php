<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 21/03/2018
 * Time: 20:01
 *
 * @var $ctrl \bbn\mvc\controller
 */
$ctrl
  ->set_color('red', 'white')
  ->set_icon('nf nf-fa-envelope')
  ->combo(_("eMails"), ['root' => APPUI_EMAILS_ROOT]);
$ctrl->obj->url = APPUI_EMAILS_ROOT . 'page';
