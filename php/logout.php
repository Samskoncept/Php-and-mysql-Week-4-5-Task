<?php

//logout session

session_start();
if (isset($_SESSION)) {
    session_unset();
    session_destroy();
    header('location:../forms/login.html');
}
