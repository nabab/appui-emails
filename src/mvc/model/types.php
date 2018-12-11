<?php
/** @var $model \bbn\mvc\model */


if ( isset($model->data['action']) ){
  $notes = new \bbn\appui\notes($model->db);
  switch ( $model->data['action'] ){
    case 'select':
      if ( isset($model->data['value']) && \bbn\str::is_integer($model->data['value']) ){
        $order = "ORDER BY nom";
        if ( isset($model->data['order'], $model->data['dir']) ){
          $fields = array_keys($model->db->get_columns("bbn_notes_masks"));
          $dirs = ['asc', 'desc'];
          if ( in_array($model->data['order'], $fields) && in_array($model->data['dir'], $dirs) ){
            $order = "ORDER BY ".$model->data['order']." ".$model->data['dir'];
          }
        }

        return [
          'total' => $model->db->count('bbn_notes_masks', ['categorie' => $model->data['value']]),
          'data' => $model->db->get_rows("
          SELECT bbn_notes_masks.*, bbn_notes.*, bbn_notes_versions.*
          FROM bbn_notes_masks
          	JOIN bbn_notes
            	ON bbn_notes.id = bbn_notes_masks.id_note
            JOIN bbn_notes_versions as v
            	ON bbn_notes_versions.id_note = bbn_notes.id
            LEFT JOIN bbn_notes_versions as vold
            	ON bbn_notes_versions.id_note = bbn_notes.id
              AND vold.version > v.version
          WHERE id_type = ?
          AND bbn_notes.active = 1
          AND vold.version IS NULL
          $order",
            $model->data['value'])
        ];
      }
      break;

    case 'defaut':
      $masks = new \bbn\appui\masks($model->db);
      $res = [
        'success' => false
      ];
      if ( \bbn\str::is_uid($model->data['id_note']) &&
        $masks->set_default($model->data['id_note'])
      ){
        $res['success'] = true;
      }
      return $res;
      break;

    case 'insert':
      $res = ['success' => false];
      if ( isset($model->data['name'], $model->data['content'], $model->data['id_user'], $model->data['title'], $model->data['id_type']) ){
        $masks = new \bbn\appui\masks($model->db);
        $defaut = 0;
        if (
          !empty($model->data['default']) &&
          $id_default = $masks->get_default($model->data['id_type'])
        ){
          $model->db->update('bbn_notes_masks', ['def' => 0], ['id_note' => $id_defaut]);
        }
        if ( $id_note = $masks->insert($model->data['name'], $model->data['id_type'], $model->data['title'], $model->data['content']) ){
          $res['data']  = $masks->get($id_note);
          $res['success'] = true;
          return $res;
        }
      }
      return "0";
      break;

    case 'update':
      $res = ['success' => false];
      if ( isset($model->data['id_note'], $model->data['content'], $model->data['id_user'], $model->data['title'],
        $model->data['name']) ){
        if ( $masks->update($model->data['id_note'], $model->data['name'], $model->data['title'], $model->data['content']) ){
          $res['success'] = true;
          $res['data'] = $masks->get($model->data['id_note']);
          $res['data']['type'] = $model->data['type'];
          $res['data']['id_type'] = $model->data['id_type'];
          return $res;
        }
      }
      return "0";
      break;

    case 'copy':
      if ( isset($model->data['id']) && ($info = $model->db->rselect('bbn_notes_masks', [], ['id_note' => $model->data['id']])) ){
        unset($info['id']);
        $info['defaut'] = 0;
        $i = 1;
        $title = $info['name'].' - copie '.$i;
        while ( $model->db->select_one('bbn_notes_masks', 'id', ['categorie' => $info['categorie'], 'nom' => $title]) ){
          $i++;
          $title = $info['nom'].' - copie '.$i;
        }
        $info['nom'] = $title;
        $model->db->insert('bbn_notes_masks', $info);
        return [
          'total' => $model->db->count('bbn_notes_masks', ['categorie' => $info['categorie']]),
          'data' => $model->db->get_rows("
          SELECT *
          FROM bbn_notes_masks
          WHERE bbn_h = 1
          AND categorie = ?",
            $info['categorie'])
        ];
      }
      return "0";
      break;

    case 'delete':
      if ( isset($model->data['id_note']) ){
        if ( $model->db->delete("bbn_notes_masks", ['id_note' => $model->data['id_note']]) ){
          $model->data['success'] = true;
          return $model->data;
        }
      }
      return "0";
      break;
  }
}
else{
  $notes = new \bbn\appui\masks($model->db);
  $masks = array_map(function($a){
    $a['content'] = '';
    return $a;
  }, $notes->get_all());
  return [
    'is_dev' => $model->inc->user->is_dev(),
    'categories' => $masks
  ];
}