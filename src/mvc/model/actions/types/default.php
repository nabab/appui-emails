<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 19:04
 *
 * @var $model \bbn\mvc\model
 */
$masks = new \bbn\appui\masks($model->db);
if ( !empty($model->data['id_note']) &&
	\bbn\str::is_uid($model->data['id_note']) &&
	$masks->set_default($model->data['id_note'])
){
	return [
		'success' => true
	];
}
