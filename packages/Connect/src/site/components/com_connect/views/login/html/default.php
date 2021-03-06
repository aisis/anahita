<?php defined('KOOWA') or die ?>

<?php $alert_title = sprintf(@text('COM-CONNECT-LOGIN-PROMPT-HI'), $api->getUser()->name, ucfirst($api->getName())) ?>
<?php $return = base64UrlEncode(KRequest::url()); ?>

<div class="row">
	<div class="span8">

    <?php if (@service('com://site/people.controller.person')->isRegistrationOpen()) : ?>
    <div class="alert alert-block">
    	<h4><?= $alert_title ?></h4>
    	<p><?= @text('COM-CONNECT-LOGIN-PROMPT-SIGN-IN-OR-SIGNUP') ?></p>
    </div>

    <div class="well">
        <a href="<?= @route('option=com_people&view=session&connect=1&return='.$return) ?>" class="btn">
            <?= @text('Sign In') ?>
        </a>

        <a href="<?= @route('option=com_people&view=person&layout=add&return='.$return) ?>" class="btn">
            <?= @text('Register') ?>
        </a>
    </div>

    <?php else : ?>
    <div class="alert alert-block">
    <h4><?= $alert_title ?></h4>
    <p><?= @text('COM-CONNECT-LOGIN-PROMPT-SIGN-IN')?></p>
    </div>

    <div class="well">
    	<a href="<?= @route('option=com_people&view=session&connect=1&return='.$return) ?>" class="btn btn-primary">
    		<?= @text('COM-CONNECT-LOGIN-PROMPT-LOGIN')?>
    	</a>
    </div>
    <?php endif;?>

	</div>
</div>
