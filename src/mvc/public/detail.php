<?php
use bbn\X;

/* @var $ctrl \bbn\Mvc\Controller */
if (X::hasProps($ctrl->post, ['uid', 'col', 'tst'], true)) {
  $ctrl->combo(_("History line"), true);
}
