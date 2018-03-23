<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:06
 *
 * @var $model \bbn\mvc\model
 */

return [
  'ready' => $model->db->get_one("
    SELECT COUNT(id)
    FROM bbn_emailings
    WHERE statut LIKE 'pret'
      AND envoi IS NOT NULL
      AND actif = 1
  "),
  'in_progress' => $model->db->get_one("
    SELECT COUNT(id)
    FROM bbn_emailings
    WHERE statut LIKE 'en cours'
      AND envoi IS NOT NULL
      AND actif = 1
  "),
  'sent'  => $model->db->get_one("
    SELECT COUNT(id)
    FROM bbn_emailings
    WHERE statut LIKE 'envoye'
      AND envoi IS NOT NULL
      AND actif = 1
  "),
  'suspended' =>  $model->db->get_one("
    SELECT COUNT(id)
    FROM bbn_emailings
    WHERE statut LIKE 'suspendu'
      AND envoi IS NOT NULL
      AND actif = 1
  "),
  'draft' =>  $model->db->get_one("
    SELECT COUNT(id)
    FROM bbn_emailings
    WHERE envoi IS NULL
      AND actif = 1
  ")
];