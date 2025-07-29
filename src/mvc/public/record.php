<?php
use bbn\X;

/* @var $ctrl \bbn\Mvc\Controller */
if (X::hasProps($ctrl->post, ['uid', 'col'], true)) {
  $ctrl->combo(_("Record"), true);
}
