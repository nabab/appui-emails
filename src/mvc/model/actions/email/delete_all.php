<?php
$success = false;
$mailings = new \bbn\appui\mailings($model->db);
 
if ( !empty($model->data['id']) ){
  $success = $mailings->delete_all_emails($model->data['id']); 
}
return [
  'success' => $success
];