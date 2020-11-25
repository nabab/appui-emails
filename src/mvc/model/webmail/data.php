<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/
$mb = new bbn\user\mailbox($model->db);
return ['data' => $mb->get_accounts()];
/*
$id_accounts = $model->inc->options->from_code('accounts', 'emails', 'appui');
$accounts = $model->inc->pref->get_all($id_accounts);
$pw = new \bbn\appui\passwords($model->db);

foreach ($accounts as $acc) {
  if ($acc['type'] === 'imap') {
    $mb = new bbn\appui\mailbox([
      'type' => $acc['type'],
      'host' => $acc['host'],
      'login' => $acc['login'],
      'pass' => $pw->user_get($acc['id'], $model->inc->user)
    ]);
    $folders = $model->inc->pref->get_bits($acc['id']);
    die(var_dump());
    //foreach ($model->inc->pref->)
    var_dump($mb->get_imap(), $mb->get_last_uid());
    // Getting last messages
    $last_message = empty($acc['last_uid']) ? 1 : $mb->get_msg_no($acc['last_uid']);
  }
}
die();
*/