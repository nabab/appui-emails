<?php
/*
 * Describe what it does!
 *
 **/
use bbn\x;

/** @var $model \bbn\mvc\model*/
$em         = new bbn\user\emails($model->db);
$pw         = new bbn\appui\passwords($model->db);
$deleted    = [];
$subscribed = [];
$added      = [];
/*
$added[]  = $em->add_account(
  [
    'pass'  => $pw->user_get('74af26b82a8d11eba49b366237393031', $model->inc->user),
    'login' => 'thomas@babna.com',
    'host'  => 'server.babna.com',
    'type'  => 'imap'
  ]
);
$added[]  = $em->delete_account('07d079aa2ba011eba49b366237393031');
*/
// CLI
$done = [];
$accounts = $em->get_accounts();
foreach ($accounts as $a) {
  $deleted[] = $em->reset($a['id']);
  /*
  $em->sync_folders($a['id']);
  x::map(
    function ($folder) use (&$em, &$done) {
      if ($folder = $em->check_folder($folder)) {
        $done[$folder['uid']] = $em->sync_emails($folder);
      }
    },
    $em->get_folders($a['id']),
    'items'
  );
  $subscribed[$a['login']] = $em->get_folders($a['id']);
  break;
  */
}

return [
  'done' => $done,
  //'types' => bbn\user\emails::get_folder_types(),
  //'folders' => bbn\user\emails::get_options('folders'),
  //'added' => $added,
  'subscribed' => $subscribed,
  //'deleted' => $deleted,
  //'data' => $accounts
];