<?php
use bbn\x;
/*
return [[
  'id' => 'appui-email-0',
  'frequency' => 30,
  'function' => function(array $data) use($model){
    $em = new bbn\user\emails($model->db);
    $accounts = $em->get_accounts();
    $tot = 0;
    foreach ($accounts as $a) {
      if ($tot < 500) {
        x::map(
          function ($folder) use (&$em, &$a, &$tot) {
            if ($tot < 500) {
              $check = $em->check_folder($folder);
              if ($check) {
                $tot += $em->sync_emails($folder, 100);
                x::log('hello from poller mail '.$tot);
              }
            }

            return $folder;
          },
          $em->get_folders($a['id']),
          'items'
        );
      }
    }
    // Update users mail
    return ['success' => true];
  }
]];
*/