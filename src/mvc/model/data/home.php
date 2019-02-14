<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 17:46
 *
 * @var $model \bbn\mvc\model
 */

/* if ( isset($model->data['start']) && !empty($model->data['limit']) ){
  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'tables' => ['bbn_emailings'],
    'fields' => [],
    'query' => "
      SELECT bbn_emailings.*,
        (
          SELECT COUNT(bbn_emails.id)
          FROM bbn_emails
          WHERE bbn_emails.id_mailing = bbn_emailings.id
        ) AS num_accuses,
        (
          SELECT COUNT(bbn_emails.id)
          FROM bbn_emails
          WHERE bbn_emails.id_mailing = bbn_emailings.id
            AND bbn_emails.etat LIKE 'succes'
        ) AS recus
      FROM bbn_emailings
        JOIN bbn_history_uids
          ON bbn_emailings.id = bbn_history_uids.bbn_uid
          AND bbn_history_uids.bbn_active = 1
    "
  ]);
  if ( $grid->check() &&
    ($ret = $grid->get_datatable())
  ){
    $notes = new \bbn\appui\notes($model->db);
    if ( !empty($ret['data']) ){
      $ret['data'] = array_map(function($d) use($notes){
        if ( !empty($d['id_note']) ){
          $note = $notes->get($d['id_note']);
          $d['texte'] = $note['content'];
          $d['objet'] = $note['title'];
          if ( !empty($note['medias']) ){
            $note['medias'] = array_map(function($m){
              return [
                'name' => $m['name'],
                'extension' => '.'.\bbn\str::file_ext($m['name'])
              ];
            }, $note['medias']);
          }
          $d['fichiers'] = $note['medias'] ?? [];
        }
        return $d;
      }, $ret['data']);
    }
    return $ret;
  }
}
 */

 if ( isset($model->data['start']) && !empty($model->data['limit']) ){
  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'tables' => ['bbn_emailings', 'bbn_notes_versions'],
    'fields' => [
      'bbn_emailings.id',
      'bbn_emailings.id_note',
      'bbn_emailings.version',
      'bbn_emailings.statut',
      'bbn_emailings.destinataires',
      'bbn_emailings.envoi',
      'bbn_notes_versions.title',
      'bbn_notes_versions.content'
    ],
    'query' => "
      SELECT bbn_emailings.*, bbn_notes_versions.title, bbn_notes_versions.content,
        (
          SELECT COUNT(bbn_emails.id)
          FROM bbn_emails
          WHERE bbn_emails.id_mailing = bbn_emailings.id
        ) AS num_accuses,
        (
          SELECT COUNT(bbn_emails.id)
          FROM bbn_emails
          WHERE bbn_emails.id_mailing = bbn_emailings.id
            AND bbn_emails.etat LIKE 'succes'
        ) AS recus
      FROM bbn_emailings
        JOIN bbn_history_uids
          ON bbn_emailings.id = bbn_history_uids.bbn_uid
          AND bbn_history_uids.bbn_active = 1
        JOIN bbn_notes
          ON bbn_emailings.id_note = bbn_notes.id
          AND bbn_notes.active = 1
        JOIN bbn_notes_versions
          ON bbn_emailings.id_note = bbn_notes_versions.id_note
          AND bbn_emailings.version = bbn_notes_versions.version
    ",
    'count' => "
      SELECT COUNT(bbn_emailings.id)
      FROM bbn_emailings
        JOIN bbn_history_uids
          ON bbn_emailings.id = bbn_history_uids.bbn_uid
          AND bbn_history_uids.bbn_active = 1
        JOIN bbn_notes
          ON bbn_emailings.id_note = bbn_notes.id
          AND bbn_notes.active = 1
        JOIN bbn_notes_versions
          ON bbn_emailings.id_note = bbn_notes_versions.id_note
          AND bbn_emailings.version = bbn_notes_versions.version
    "
  ]);
  
  if ( $grid->check() &&
    ($ret = $grid->get_datatable())
  ){
    $notes = new \bbn\appui\notes($model->db);
    if ( !empty($ret['data']) ){
      $ret['data'] = array_map(function($d) use($notes){
        if ( !empty($d['id_note']) ){
          if ( $medias = $notes->get_medias($d['id_note']) ){
            $medias = array_map(function($m){
              return [
                'name' => $m['name'],
                'extension' => '.'.\bbn\str::file_ext($m['name'])
              ];
            }, $medias);
          }
          $d['fichiers'] = $medias;
        }
        return $d;
      }, $ret['data']);
    }
    return $ret;
  }
}
