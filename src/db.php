<?php

/**
 *
 * Подключили БД
 *
 */
$mysql = mysqli_connect("localhost", "root", "", "readme");
mysqli_set_charset($mysql, "utf8");
