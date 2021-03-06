<?php
switch ($modx->event->name) { // as usual, check event name for run it only for needed event, if plugin attached to several events
    case 'OnManagerLogin':
        $slackify = $modx->getService('slackify'); // Important, you should initialize Slackify service before usage
        // modx will load class Slackify and all related classes for work
        // create message
        $sitename = $modx->getOption('site_name');
        $siteurl = $modx->getOption('site_url');
        if (!$user) break;
        $creatorProfile = $user->getOne('Profile');
        if (!$creatorProfile) break;
        $a = new Attachment();
        $a->setColor(new Color('#666666'));
        $a->setAuthor(new Author($creatorProfile->get('fullname'), 'mailto:' . $creatorProfile->get('email')));
        $a->setTitle(new Title("logged in"));
        $a->addField(new Field('When', (new DateTime())->format('d M Y - G:i:s'), true));
        $a->addField(new Field('User IP', $_SERVER['REMOTE_ADDR'], true));
        $a->addField(new Field('User agent', $_SERVER['HTTP_USER_AGENT']));
        $message = new Message('*manager login* on '.$sitename.' at '.$siteurl);
        $message->attach($a); // attach attachment to message. You cn create several attachments and attach their to message

        $slackify->send($message); // and send message to Slack
    break;
}