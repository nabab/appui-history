<?php
/** @var $ctrl \bbn\Mvc\Controller */

$d = $ctrl->getPluginModel('list', $ctrl->data);
if ( is_null($d) ){
  $d = $ctrl->getModel();
}
$ctrl->setTitle($d['title'] ?? _("History of use"));
$views = $ctrl->getPluginViews('list', $d);
$ctrl->obj->data = $d;
echo $views['html'] ?: $ctrl->getView();
$ctrl->addScript($views['js'] ?: $ctrl->getView('', 'js'));
$ctrl->obj->css = $views['css'] ?: $ctrl->getLess();