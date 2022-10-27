<?php

require_once '../config.php';

//register users
function registerUser($fullnames, $email, $password, $gender, $country)
{
    $conn = db();
    if (
        mysqli_num_rows(
            mysqli_query(
                $conn,
                "SELECT email from students WHERE email='$email'"
            )
        ) >= 1
    ) {
        echo "<script> alert('User already exists')</script>";
        header('refresh:1;url=../forms/login.html');
    } else {
        $sql = "INSERT INTO students(full_names,country,email,gender,`password`) VALUES ('$fullnames','$country','$email','$gender','$password')";

        if (mysqli_query($conn, $sql)) {
            echo "<script> alert('User succesfully registered')</script>";
            session_start();
            $_SESSION['username'] = $email;
            header('refresh:1; url=../dashboard.php');
        }
    }
}

//login users
function loginUser($email, $password)
{
    $conn = db();

    $query = "SELECT * FROM students WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) >= 1) {
        session_start();
        $_SESSION['username'] = $email;
        header('location:../dashboard.php');
    } else {
        echo "<script>alert('Wrong email/Password')</script>";
        header('refresh:2;url=../forms/login.html');
    }
}

function resetPassword($email, $password)
{
    $conn = db();
    $query = " SELECT email FROM students WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) >= 1) {
        $sql = "UPDATE students set password='$password' where email='$email'";
        if (mysqli_query($conn, $sql)) {
            echo 'Password changed sucessfully';
        } else {
            echo "<script>alert('an error occured,try again')</script>";
        }
    } else {
        echo "<script>alert('User does not exist')</script>";
        header('refresh:2;url=../forms/login.html');
    }
}

function getusers()
{
    $conn = db();
    $sql = 'SELECT * FROM Students';
    $result = mysqli_query($conn, $sql);
    echo "<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            //show data
            echo "<tr style='height: 30px'>" .
                "<td style='width: 50px; background: blue'>" .
                $data['id'] .
                "</td>
                <td style='width: 150px'>" .
                $data['full_names'] .
                "</td> <td style='width: 150px'>" .
                $data['email'] .
                "</td> <td style='width: 150px'>" .
                $data['gender'] .
                "</td> <td style='width: 150px'>" .
                $data['country'] .
                "</td> <td style='width: 150px'> 
                <form action='action.php' method='POST'>
                <input type='hidden' name='id'" .
                'value=' .
                $data['id'] .
                '>' .
                "<button type='submit', name='delete'> DELETE </button></form></td>" .
                '</tr>';
        }
        echo '</table></table></center></body></html>';
    }
    //return users from the database
    //loop through the users and display them on a table
}

function deleteaccount($id)
{
    $conn = db();
    if (
        mysqli_num_rows(
            mysqli_query($conn, "SELECT * FROM students WHERE id=$id")
        ) >= 1
    ) {
        $sql = "DELETE FROM students WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('deleted sucessfully')</script><br>";
            header('refresh:1;url=action.php?all=');
        } else {
            echo "<script>alert('error')</script>";
        }
    }
}
