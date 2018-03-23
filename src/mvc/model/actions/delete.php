<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 18:00
 *
 * @var $model \bbn\mvc\model
 */

if ( !empty($model->data['id']) ){
  //NEW
  //we take the information related to the email to be deleted so we could go back to id_media
  $email = $model->db->rselect("bbn_emailings", [], ['id' => $model->data['id']]);
  //$notes->remove($email['id_note']);
  $media =  $model->db->rselect("bbn_notes_medias", [], [
    'id_note' => $email['id_note'],
    'version' => $email['version']
  ]);
  if ( $model->db->delete("bbn_emailings", ['id' => $model->data['id']]) ){
    if( is_dir(BBN_DATA_PATH.'medias/'.$media['id_media']) ){
      \bbn\file\dir::delete(BBN_DATA_PATH.'medias/'.$media['id_media']);
    }
    $model->data['count'] = [
      'pret' => $model->db->get_one("
                SELECT COUNT(id) FROM bbn_emailings
                WHERE statut = 'pret' AND envoi IS NOT NULL AND actif = 1"),
      'en_cours' => $model->db->get_one("
                SELECT COUNT(id) FROM bbn_emailings
                WHERE statut = 'en cours' AND envoi IS NOT NULL AND actif = 1"),
      'envoi'  => $model->db->get_one("
                SELECT COUNT(id) FROM bbn_emailings
                WHERE statut = 'envoye' AND envoi IS NOT NULL AND actif = 1"),
      'suspendu' =>  $model->db->get_one("
                SELECT COUNT(id) FROM bbn_emailings
                WHERE statut = 'suspendu' AND envoi IS NOT NULL AND actif = 1"),
      'draft' =>  $model->db->get_one("
                SELECT COUNT(id) FROM bbn_emailings
                WHERE envoi IS NULL AND actif = 1"),
    ];
    return $model->data;
  }
}