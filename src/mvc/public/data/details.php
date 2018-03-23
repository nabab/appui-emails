<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 17:46
 *
 * @var $ctrl \bbn\mvc\controller
 */
 if ( !empty($ctrl->arguments[0]) && \bbn\str::is_uid($ctrl->arguments[0]) ){
	 $ctrl->obj = $ctrl->get_model(['id' => $ctrl->arguments[0]]);
 }
