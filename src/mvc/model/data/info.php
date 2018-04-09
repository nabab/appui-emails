<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

$current = $model->db->get_row("
  SELECT id, envoi AS moment, destinataires AS recipients, bbn_notes_versions.title AS title
  FROM bbn_emailings
    JOIN bbn_notes_versions
      ON bbn_emailings.id_note = bbn_notes_versions.id_note
      AND bbn_emailings.version = bbn_notes_versions.version
  WHERE statut LIKE 'en cours'
    AND actif = 1
");
$current['sent'] = !empty($current['id']) ? $model->db->count('bbn_emails', [
  'id_mailing' => $current['id'],
  'etat' => 'succes'
]) : 0;
$next = $model->db->get_row("
  SELECT id, envoi AS moment, destinataires AS recipients, bbn_notes_versions.title AS title
  FROM bbn_emailings
    JOIN bbn_notes_versions
      ON bbn_emailings.id_note = bbn_notes_versions.id_note
      AND bbn_emailings.version = bbn_notes_versions.version
  WHERE statut LIKE 'pret'
    AND envoi IS NOT NULL
    AND actif = 1
  ORDER BY envoi ASC
");
return [
	'success' => true,
  'data' => [
    'current' => $current,
    'next' => $next
  ],
  'count' => !empty($model->data['updateCount']) ? $model->get_model(APPUI_EMAILS_ROOT.'data/count') : false
];
