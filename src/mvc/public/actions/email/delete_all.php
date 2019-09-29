<?php
if ( !empty($ctrl->post['id']) || !empty($ctrl->post['selected'])){
  $ctrl->action();
}