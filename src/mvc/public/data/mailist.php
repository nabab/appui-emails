<?php
/** @var \bbn\mvc\controller $ctrl */
if (!empty($ctrl->arguments[0]) && ($ctrl->arguments[0] === 'num')) {
  $ctrl->add_data(['num' => true]);
}
$ctrl->action();