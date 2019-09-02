<?php
$r = [
  'success' => false,
  'content'=> []
];

if ( isset($model->data['id']) ){
  $lettre = new \bbn\appui\masks($model->db);
  $item = $lettre->get($model->data['id']);
  if ( $item ){
    /** @todo change the variables!!! */
    $r['success'] = true;
    $r['content']['texte'] = $item['content'];
    $r['content']['titre'] = $item['title'];
  }
}
return $r;
