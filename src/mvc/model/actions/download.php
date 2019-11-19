<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/
$file = false;
if (\bbn\x::has_props($model->data, ['id_media', 'id'])) {
  $mailing = new \bbn\appui\mailings($model->db);
  if ($medias = $mailing->get_medias($model->data['id'])) {
    $idx = \bbn\x::find($medias, ['id' => $model->data['id_media']]);
    if ($medias[$idx]) {
      $file = $medias[$idx]['file'];
    }
  }
}
return ['file' => $file];