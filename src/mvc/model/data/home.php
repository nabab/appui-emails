<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 17:46
 *
 * @var $model \bbn\mvc\model
 */
if ( isset($model->data['start']) && !empty($model->data['limit']) ){
  $grid = new \bbn\appui\grid($model->db, $model->data, [
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
    ",
    'count' => "
      SELECT COUNT(id) 
      FROM bbn_emailings
    ",
    'filters' => ['actif' => 1]
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
          $d['fichiers'] = $note['medias'];
        }
        return $d;
      }, $ret['data']);
    }
    return $ret;
  }
}