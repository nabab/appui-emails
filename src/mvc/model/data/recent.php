<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
$mailings = new \bbn\appui\mailings($model->db);
return ['last' => $mailings->get_lasts(), 'next' => $mailings->get_nexts(), 'sendings' => $mailings->get_sendings()];