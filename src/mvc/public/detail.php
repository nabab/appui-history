<?php
use bbn\X;

/* @var bbn\Mvc\Controller $ctrl */
if (X::hasProps($ctrl->post, ['uid', 'col', 'tst'], true)) {
  $ctrl->combo(_("History line"), true);
}
