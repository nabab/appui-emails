<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 15:39
 *
 * @var $model \bbn\mvc\model
 */
if ($model->check_action(['title', 'id', 'content', 'sender'], true)) {
  $attachments = [];
  if ( \bbn\x::has_props($model->data, ['attachments', 'ref'], true) ){
    $temp_path = $model->user_tmp_path().$model->data['ref'].'/';
    foreach ( $model->data['attachments'] as $f ){
      $attachments[] = is_file($temp_path.$f['name']) ? $temp_path.$f['name'] : $f;
    }
  }
  $model->data['attachments'] = $attachments;
  $mailings = new \bbn\appui\mailings($model->db);
  $data = empty($model->data['sent']) ? [] : $model->get_plugin_model('data/mailist', $model->data, 'emails');
  $model->data['emails'] = $data['data'];
  if ($model->data = $mailings->edit($model->data['id'], $model->data)) {
    if (
      !empty($data['success'])
      && isset($data['data'])
      && $model->has_data('sent', true)
    ) {
      $model->data['res'] = $data['data'];
      $data = $model->get_plugin_model('data/result', $model->data, 'emails');
      $message = _('The mailing has been modified with all its recipients');
    }
    else{
      $message = _('The mailing has been modified and will be sent accordingly to your settings.');
    }
    return [
      'success' => true,
      'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count'),
      'message' => $message
    ];
  }
}