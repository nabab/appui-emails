<?php
/* @var \bbn\mvc\model $model */
if ( 
  !empty($model->data['id_note']) && 
  !empty($model->data['version']) &&
  ($notes = new \bbn\appui\note($model->db)) &&
  ($version = $notes->get_full($model->data['id_note'], $model->data['version'])) &&
  ($ftype = $model->inc->options->from_root_code('file', 'media', 'note', 'appui')) &&
  ($ltype = $model->inc->options->from_root_code('link', 'media', 'note', 'appui'))
){
  $version['name'] = $model->db->select_one([
    'table' => 'bbn_notes_masks',
    'fields' => ['name'],
    'where' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'id_note',
        'operator' => '=',
        'value' => $version['id']
      ]]
      ],
  ]);
  $version['files'] = [];
  $version['links'] = [];
  foreach ( $version['medias'] as $m ){
    if ( $m['type'] === $ftype ){
      $version['files'][] = [
        'id' => $m['id'],
        'name' => $m['name'],
        'title' => $m['title'],
        'extension' => '.'.\bbn\str::file_ext($m['name'])
      ];
    }
    if ( $m['type'] === $ltype ){
      $version['links'][] = $m;
    }
  }
  unset($version['medias']);
  return [
    'success' => true,
    'data' => $version
  ];
}
return ['success' => false];