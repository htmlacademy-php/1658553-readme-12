<?php

session_start();
/**
 *
 * Подключили БД
 *
 */
$mysql = mysqli_connect("localhost", "root", "", "readme");
mysqli_set_charset($mysql, "utf8");

$isAuth = empty($_SESSION['user']);

const TYPE_TEXT = 'text';
const TYPE_QUOTE = 'quote';
const TYPE_PHOTO = 'photo';
const TYPE_VIDEO = 'video';
const TYPE_LINK = 'link';


const SORT_VIEWS = 'views_number';
const SORT_DATE = 'create_date';
const SORT_LIKES = 'count_likes';

const PAGE_MESSAGES = '/messages.php';
const PAGE_FEED = '/feed.php';
const PAGE_POPULAR = '/popular.php';


const SHOW_POSTS = 'posts';
const SHOW_LIKES = 'likes';
const SHOW_SUBSCRIBE = 'subscribe';

error_reporting(E_ALL);
