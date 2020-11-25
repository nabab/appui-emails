<?php
/**
 *  Describe what it does or you're a pussy
 *
 **/
$user = false;
$id_accounts = $ctrl->inc->options->from_code('accounts', 'emails', 'appui');

$accounts = $ctrl->inc->pref->get_all($id_accounts);