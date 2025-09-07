<?php
use bbn\X;

/* @var bbn\Mvc\Controller $ctrl */
if (X::hasProps($ctrl->post, ['uid', 'col'], true)) {
  $ctrl->combo(false, true);
}
