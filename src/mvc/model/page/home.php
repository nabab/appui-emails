<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:02
 *
 * @var $model \bbn\mvc\model
 */


//$recipients = $model->inc->options->full_options($model->inc->options->from_code('emails_listes'));
return [
  'root' => APPUI_EMAILS_ROOT,
  'root_usergroup' => $model->plugin_url('appui-usergroup').'/',
  'types' => $model->db->get_rows("
    SELECT bbn_notes_masks.id_note AS id, bbn_notes_masks.name AS text
    FROM bbn_notes_masks
      JOIN bbn_notes_versions
        ON bbn_notes_versions.id_note = bbn_notes_masks.id_note
      LEFT JOIN bbn_notes_versions AS v_old
        ON (bbn_notes_versions.id_note = bbn_notes_masks.id_note
        AND bbn_notes_versions.version > v_old.version)
    WHERE v_old.id_note IS NULL
    GROUP BY bbn_notes_masks.id_note
    ORDER BY text
  "),
  'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count'),
  'recipients' => $model->inc->options->text_value_options('emails_listes'),
  'senders' => array_map(function($a){
    return [
      'text' => $a['text'],
      'value' => $a['id'],
      'desc' => $a['desc']
    ];
  }, $model->inc->options->full_options('sender', 'mailing', 'appui'))
];
