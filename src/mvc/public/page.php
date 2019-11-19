<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 21/03/2018
 * Time: 20:01
 *
 * @var $ctrl \bbn\mvc\controller
 */

	
if ( empty($ctrl->arguments) || ($ctrl->arguments[0] === 'home') ){
	$ctrl->obj->url = APPUI_EMAILS_ROOT . 'page';
	$ctrl->set_color('teal', 'white')
		->set_icon('nf nf-fa-envelope')
		->combo(_("eMails"), ['root' => APPUI_EMAILS_ROOT]);
}
else {
	$ctrl->reroute(APPUI_EMAILS_ROOT . 'page/'.$ctrl->arguments[0]);
}
//$ctrl->obj->url = APPUI_EMAILS_ROOT . 'page';
