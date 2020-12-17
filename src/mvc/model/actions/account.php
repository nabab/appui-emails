<?php
/*
 * Describe what it does!
 *
 **/
use bbn\x;

/** @var $model \bbn\mvc\model */
if ($model->has_data('action')) {
  switch ($model->data['action'])
  {
    case 'test':
    case 'save':
      if ($model->has_data(['type', 'login', 'pass', 'email'], true)
          && ($code = $model->inc->options->code($model->data['type']))
      ) {
        $cfg = [
          'type' => $code,
          'login' => $model->data['login'],
          'host' => $model->data['host'] ?? null,
          'pass' => $model->data['pass'],
        ];
        $mb = new bbn\appui\mailbox($cfg);
        if ($mb->get_status() === 'ok') {
          if ($model->data['action'] === 'test') {
            $model->data['res']['success'] = true;
            $res = [];
            $subscribed = $mb->list_all_subscribed();
            $mbParam = $mb->get_params();
            $put_in_res = function (array $a, &$res, $prefix = '') use (&$put_in_res, &$subscribed, $mbParam) {
              $ele = array_shift($a);
              $idx = x::find($res, ['text' => $ele]);
              if (null === $idx) {
                $idx   = count($res);
                $res[] = [
                  'text' => $ele,
                  'uid' => $prefix.$ele,
                  'items' => [],
                  'subscribed' => in_array($mbParam.$prefix.$ele, $subscribed)
                ];
              }

              if (count($a)) {
                $put_in_res($a, $res[$idx]['items'], $prefix.$ele.'.');
              }
            };

            foreach ($mb->list_all_folders() as $dir) {
              $tmp = str_replace($mbParam, '', $dir);
              $bits = x::split($tmp, '.');
              $put_in_res($bits, $res);
            }
            $model->data['res']['data'] = $res;
            break;
          }
          elseif ($model->has_data(['email', 'folders'], true)
              && is_array($model->data['folders'])
          ) {
            unset($mb);
            $em = new bbn\user\emails($model->db);
            $cfg['folders'] = $model->data['folders'];
            $cfg['email'] = $model->data['email'];
            if ($id_account = $em->add_account($cfg)) {
              $model->data['res']['success'] = true;
            }
          }
        }
      }
      break;

    case 'delete':
      break;
    case 'update':
      break;
    case 'insert':
      break;
  }
}
return $model->data['res'];