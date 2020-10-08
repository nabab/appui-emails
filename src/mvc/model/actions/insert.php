<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 15:39
 *
 * @var $model \bbn\mvc\model
 */
if ($model->check_action(['content', 'title', 'sender'], true)) {
  $attachments = [];
  $medias = [];
  $mailings = new \bbn\appui\mailings($model->db);
  // If files are sent
  if ( \bbn\x::has_props($model->data, ['attachments', 'ref'], true) ){
    // If duplicating id_parent is sent
    if (!empty($model->data['id_parent'])
        && ($o = $mailings->get_mailing($model->data['id_parent']))
    ) {
      // Getting its medias
      $medias = $mailings->get_medias($model->data['id_parent'], $o['version']);
    }
    // Path where temp files are stored
    $temp_path = $model->user_tmp_path().$model->data['ref'].'/';
    // Checking attachments
    foreach ( $model->data['attachments'] as $f ){
      $idx = false;
      // File is sent
      if (is_file($temp_path.$f['name'])) {
        $attachments[] = $temp_path.$f['name'];
      }
      // File comes from parent
      else if (!empty($model->data['id_parent'])) {
        $idx = \bbn\x::find($medias, ['name' => $f['name']]);
        if ($idx !== null) {
          // Sending an array with id_media
          $attachments[] = ['id_media' => $f['id']];
        }
      }
    }
  }
  $data = empty($model->data['sent']) ? [] : $model->get_plugin_model('data/mailist', $model->data, 'emails');
  if ($model->data = $mailings->add([
    'content' => $model->data['content'],
    'title' => $model->data['title'],
    'sender' => $model->data['sender'],
    'recipients' => $model->data['recipients'],
    'sent' => $model->data['sent'],
    'priority' => $model->data['priority'] ?? 5,
    'attachments' => $attachments,
    'emails' => $data['data'] ?? []
  ])){
    if (
      !empty($data['success'])
      && isset($data['data'])
      && $model->has_data('sent', true)
    ) {
      $data = $model->get_plugin_model('data/result', $model->data, 'emails');
      $message = _('The mailing has been inserted with all its recipients');
    }
    else{
      $message = _('The mailing has been inserted and will be sent accordingly to your settings.');
    }
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