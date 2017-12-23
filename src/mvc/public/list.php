<?php
/** @var $ctrl \bbn\mvc\controller */
$ctrl->data = $ctrl->post;
if ( isset($ctrl->data['limit'], $ctrl->data['start']) ){
  $ctrl->action();
  }
else {
  $ctrl->combo(_("History of use"), true);
}