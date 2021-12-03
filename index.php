<?php
session_start();

if (empty($_SESSION)) {
    header('location: main.php');
} else {
    header('location: feed.php');
}
