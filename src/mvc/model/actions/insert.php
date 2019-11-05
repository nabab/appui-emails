<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 15:39
 *
 * @var $model \bbn\mvc\model
 */

if ( empty($model->data['recipients']) && !empty($model->data['sent']) ){
  return false;
}
if ( isset($model->data['content'], $model->data['title'], $model->data['sender']) ){
  $mailings = new \bbn\appui\mailings($model->db);
  if ( empty($model->data['sent']) || !\bbn\date::validateSQL($model->data['sent']) ){
    $model->data['sent'] = null;
  }
  // For duplicating
  if (!empty($model->data['id_parent'])) {
    $o = $mailings->get_mailing($model->data['id_parent']);
    if ($o) {
      $medias = $mailings->get_medias($o['id'], $o['version']);
      die(\bbn\x::dump($medias));
    }
  }
  $attachments = [];
  if ( \bbn\x::has_props($model->data, ['attachments', 'ref']) ){
    $temp_path = BBN_USER_PATH.'tmp/'.$model->data['ref'].'/';
    foreach ( $model->data['attachments'] as $f ){
      if ( is_file($temp_path.$f['name']) ){
        // Add media
        $attachments[] = $model->data['ref'].'/'.$f['name'];
      }
      else if (isset($model->data['id_parent'])) {
        
      }
    }
  }
  if ( $model->data['id'] = $mailings->add([
    'content' => $model->data['content'],
    'title' => $model->data['title'],
    'sender' => $model->data['sender'],
    'recipients' => $model->data['recipients'],
    'sent' => $model->data['sent'],
    'attachments' => $attachments
  ]) ){
    if (!empty($model->data['sent'])) {
      $num_undone = 0;
      $num_done = 0;
      $num_undone = 0;
      $num_emails = 0;
      $res = [];
      $data = $model->get_plugin_model('data/mailist', $model->data, 'emails');
      if (!empty($data['success']) && isset($data['data'])) {
        foreach ($data['data'] as $item) {
          $num_emails++;
          if ($id_email = $model->db->select_one('bbn_emails', 'id', [
            'id_mailing' => $model->data['id'],
            'email' => $item['email']
          ])) {
            $num_undone++;
          }
          else {
            $num_done++;
            $model->db->insert('bbn_emails', [
              'email' => $item['email'],
              'id_mailing' => $mailing['id'],
              'status' => 'ready',
              'delivery' => $mailing['sent']
            ]);
            $id_email = $model->db->last_id();
          }
          $item['id'] = $id_email;
          $res[] = $item;
        }
      }
      $model->data['res'] = $res;
      $keys = array_keys($mailing);
      $keys[] = 'success';
      $data = $model->get_plugin_model('data/result', $model->data, 'emails');
      $res = [];
      foreach ($data as $k => $v) {
        if (!in_array($k, $keys, true)) {
          $res[$k] = $v;
        }
      }
      $message = _('Mailing ID').': '.$model->data['id'].'<br>'.
              _('With the title').': '.$model->data['title'].'<br>'.
              _('Number of users with a valid email address').': '.$num_emails.'<br>'.
              _('Number of users treated').': '.$num_done.'<br>'.
              _('Number of users already treated').': '.$num_undone.'<br>'.
              _('Values returned by result model').': <br><pre>'.print_r($res, true).'</pre>';
    }
    else{
      $message = _('The mailing has been inserted but will not be sent until a delivery date is chosen');
    }
    return [
      'success' => true,
      'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count'),
      'message' => $message
    ];
  }
}