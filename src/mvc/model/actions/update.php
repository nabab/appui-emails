<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 18:00
 *
 * @var $model \bbn\mvc\model
 */
if ( empty($model->data['destinataires']) && !empty($model->data['envoi']) ){
  return false;
}
if ( !empty($model->data['id']) &&
  !empty($model->data['texte']) &&
  !empty($model->data['objet']) &&
  !empty($model->data['ref']) &&
  isset($model->data['destinataires'])
){
  $notes = new \bbn\appui\notes($model->db);
  // Get emailing's info
  $info = $model->db->rselect('bbn_emailings', [], ['id' => $model->data['id']]);
  if ( !empty($info['id_note']) && !empty($info['version']) ){
    // Get note's info
    $note = $notes->get($info['id_note'], $info['version']);
    // Insert the new note's verion and get the version number
    if ( $notes->insert_version($info['id_note'], $model->data['objet'], $model->data['texte']) &&
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
          else if ( ($idx = \bbn\x::find($note['medias'], ['name' => $f['name']])) !== false ) {
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
          'destinataires' => $model->data['destinataires'],
          'envoi' => !empty($model->data['envoi']) && \bbn\date::validateSQL($model->data['envoi']) ? $model->data['envoi'] : NULL
        ], ['id' => $model->data['id']])
      ];
    }
  }
}