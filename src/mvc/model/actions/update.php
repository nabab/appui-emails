<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 18:00
 *
 * @var $model \bbn\mvc\model
 */
if ( empty($model->data['recipients']) && !empty($model->data['sent']) ){
  return false;
}
if ( !empty($model->data['id']) &&
  !empty($model->data['content']) &&
  !empty($model->data['title']) &&
  !empty($model->data['ref']) &&
  isset($model->data['recipients'])
){
  $notes = new \bbn\appui\notes($model->db);
  // Get emailing's info
  $info = $model->db->rselect('bbn_emailings', [], ['id' => $model->data['id']]);
  if ( !empty($info['id_note']) && !empty($info['version']) ){
    // Get note's info
    $note = $notes->get($info['id_note'], $info['version']);
    // Insert the new note's verion and get the version number
    if ( $notes->insert_version($info['id_note'], $model->data['title'], $model->data['content']) &&
      ($version = $notes->latest($info['id_note']))
    ){
      $ok = true;
      // Files
      if ( !empty($model->data['fichiers']) ){
        $temp_path = BBN_USER_PATH.'tmp/'.$model->data['ref'].'/';
        foreach ( $model->data['fichiers'] as $f ){
          if ( is_file($temp_path.$f['name']) ){
            // Add the new file to the new version
            if ( !$notes->add_media($info['id_note'], $temp_path.$f['name']) ){
             $ok = false;
            }
          }
          else if ( !empty($note['medias']) && is_array($note['medias']) && (($idx = \bbn\x::find($note['medias'], ['name' => $f['name']])) !== false) ) {
            // Add an existing media to the new version
            if ( !$notes->media2version($note['medias'][$idx]['id'], $info['id_note'], $version) ){
              $ok = false;
            }
          }
        }
      }
      return [
        'success' => !empty($ok) && $model->db->update('bbn_emailings', [
          'version' => $version,
          'recipients' => $model->data['recipients'],
          'sent' => !empty($model->data['sent']) && \bbn\date::validateSQL($model->data['sent']) ? $model->data['sent'] : NULL
        ], ['id' => $model->data['id']]),
        'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count')
      ];
    }
  }
}