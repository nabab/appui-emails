<?php
return [[
  'id' => 'appui-emails-0',
  'frequency' => 10,
  'function' => function(array $data) use($model){
    // Update users mail
    return ['success' => true];
  }
]];
