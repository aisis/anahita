<?php defined('KOOWA') or die; ?>

<?= @helper('ui.header') ?>

<?php
$url = $item->getURL().'&layout=taggables';

if (!empty($sort)) {
    $url .= '&sort='.$sort;
}

if (!empty($scope)) {
    $url .= '&scope='.$scope;
}
?>

<?= @infinitescroll($item->tagables->fetchSet(), array(
  'url' => $url,
  'id' => 'an-hashtag-taggables'
)) ?>
