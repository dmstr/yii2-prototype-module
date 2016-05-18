<?php
$I = new AcceptanceTester($scenario);

$I->wantTo('sign in');
$I->amOnPage('/user/login');
$I->fillField('#login-form-login', 'admin');
$I->fillField('#login-form-password', 'admin');
$I->click('Sign in', '#login-form');
$I->wait(5);
$I->see('admin', '#link-user-menu');
$I->makeScreenshot('login-success');