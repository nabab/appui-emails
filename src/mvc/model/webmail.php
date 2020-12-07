<?php
/*
 * Describe what it does!
 *
 **/
use bbn\x;

$em = new bbn\user\emails($model->db);
if ($model->has_data('limit')) {
  if (!$model->has_data('id_folder', true)) {
    $model->data['id_folder'] = 'inbox';
  }
  return $em->get_list($model->data['id_folder'], $model->data);
}
else {
  return [
    'accounts' => $em->get_accounts(),
    'types' => bbn\user\emails::get_account_types(),
    'contacts' => $em->get_contacts(),
    'folder_types' => bbn\user\emails::get_folder_types()
  ];
}