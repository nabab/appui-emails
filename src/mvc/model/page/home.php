<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:02
 *
 * @var $model \bbn\mvc\model
 */

return [
  'root' => APPUI_EMAILS_ROOT,
  'types' => $model->db->get_rows("
    SELECT bbn_notes_masks.id_note AS id, bbn_notes_versions.title AS text
    FROM bbn_notes_masks
      JOIN bbn_notes_versions
        ON bbn_notes_versions.id_note = bbn_notes_masks.id_note
      LEFT JOIN bbn_notes_versions AS v_old
        ON (bbn_notes_versions.id_note = bbn_notes_masks.id_note
        AND bbn_notes_versions.version > v_old.version)
    WHERE v_old.id_note IS NULL
    GROUP BY bbn_notes_masks.id_note
  "),
  'count' => $model->get_model(APPUI_EMAILS_ROOT.'data/count'),
  'recipients' => $model->inc->options->text_value_options('emails_listes')
];
