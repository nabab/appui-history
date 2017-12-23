<?php
/* @var $ctrl \bbn\mvc\controller */
if ( !empty($ctrl->post['uid']) && !empty($ctrl->post['col']) && !empty($ctrl->post['tst']) ){
  $ctrl->combo(_("History line"), true);
}