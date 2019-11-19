<?php

$ctrl->obj->url= APPUI_EMAILS_ROOT.'page/sent';
$ctrl
  ->set_icon('nf nf-fa-envelope')
  ->set_url(APPUI_EMAILS_ROOT.'page/sent')
  ->combo(_("e-Mails sent"));