<?php
/*
 * Describe what it does!
 *
 **/
use bbn\x;

$em         = new bbn\user\emails($model->db);
return [
  'accounts' => $em->get_accounts(),
  'folder_types' => bbn\user\emails::get_folder_types()
];