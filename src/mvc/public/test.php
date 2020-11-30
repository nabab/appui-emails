<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller 
 *
 */
use bbn\x;

$em         = new bbn\user\emails($ctrl->db);
$pw         = new bbn\appui\passwords($ctrl->db);
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
  $em->sync_folders($a['id']);
  /*
  if ($a['host'] === 'server.babna.com') {
    x::map(
      function ($folder) use (&$em, &$done) {
        var_dump($folder);
        if ($folder === 'Perso') {
          if ($em->check_folder($folder)) {
            $done[$folder['uid']] = $em->sync_emails($folder);
          }
        }
        return $folder;
      },
      $em->get_folders($a['id']),
      'items'
    );
    $subscribed[$a['login']] = $em->get_folders($a['id']);
  }
  */
}

x::adump([
  'done' => $done,
  //'types' => bbn\user\emails::get_folder_types(),
  //'folders' => bbn\user\emails::get_options('folders'),
  //'added' => $added,
  'subscribed' => $subscribed,
  //'deleted' => $deleted,
  //'data' => $accounts
]);