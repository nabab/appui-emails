<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 18:00
 *
 * @var $model \bbn\mvc\model
 */
if ( !empty($model->data['id']) &&
  ( $email = $model->db->rselect('bbn_emailings', ['id_note', 'version', 'sent', 'recipients'], ['id' => $model->data['id']])) 
  
){
  $email['state'] = 'ready';
  $email['sent'] = null;
  $model->db->insert('bbn_emailings', $email);
  return [
    'success' => true,
    'id' => $model->db->last_id(),
    'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count')
  ];
}