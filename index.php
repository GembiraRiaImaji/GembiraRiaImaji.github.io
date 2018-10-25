<?php

require_once __DIR__.'/gplus-lib/vendor/autoload.php';
const CLIENT_ID = '678729879830-qegu1prbmt9q79rvgeehjkahfrs27rbr.apps.googleusercontent.com';
const CLIENT_SECRET = '6s1HO1izJ9S6kkHZl3EFf-G2';
const REDIRECT_URI = 'http://befriendsgri.000webhostapp.com/Google/index.php';

session_start();


$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setScopes('email');

$plus = new Google_Service_Plus($client);


if (isset($_GET['logout'])) {
   session_unset();
   session_destroy();
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $me = $plus->people->get('me');

  $id = $me['id'];
  $name =  $me['displayName'];
  $birthday =  $me['birthday'];
  $email =  $me['emails'][0]['value'];
  $profile_image_url = $me['image']['url'];
  $cover_image_url = $me['cover']['coverPhoto']['url'];
  $profile_url = $me['url'];
  $aboutMe = $me['aboutMe'];
  $nickname = $me['nickname'];
  $tagline = $me['tagline'];
  $braggingRights = $me['braggingRights'];
} else {
  $authUrl = $client->createAuthUrl();
}



if (isset($authUrl)) {
  header('Location: '.$authUrl);
} else {
  if (!isset($_GET['data'])) {
    echo "<title>Logged in</title>";
  } else {
    if ($_GET['data'] == "id") {
      echo "<title>".$id."</title>";
    } elseif ($_GET['data'] == "name") {
      echo "<title>".$name."</title>";
    } elseif ($_GET['data'] == "birthday") {
      echo "<title>".$birthday."</title>";
    } elseif ($_GET['data'] == "email") {
      echo "<title>";
      foreach ($me['emails'] as $email) {
        echo $email['value']."\n";
      }
      echo "</title>";
    } elseif ($_GET['data'] == "profile_img") {
      echo "<title>".$profile_image_url."0</title>";
    } elseif ($_GET['data'] == "cover_img") {
      echo "<title>".$cover_image_url."</title>";
    } elseif ($_GET['data'] == "url") {
      echo "<title>".$profile_url."</title>";
    } elseif ($_GET['data'] == "about") {
      echo "<title>".$aboutMe."</title>";
    } elseif ($_GET['data'] == "nickname") {
      echo "<title>".$nickname."</title>";
    } elseif ($_GET['data'] == "tagline") {
      echo "<title>".$tagline."</title>";
    } elseif ($_GET['data'] == "bragging") {
      echo "<title>".$braggingRights."</title>";
    } else {
      echo $id."<br>";
      echo $name."<br>";
      echo $birthday."<br>";
      echo $email."<br>";
      echo $profile_image_url."<br>";
      echo $cover_image_url."<br>";
      echo $profile_url."<br>";
      echo $aboutMe."<br>";
      echo $nickname."<br>";
      echo $tagline."<br>";
      echo $braggingRights."<br>";
    }
  }
}
