<?php 

function dbcon()
{
    global $mysqli;

    $mysqli = mysqli_connect('localhost','root','');
    mysqli_select_db($mysqli,"mail_db") or die("database not found");
    if(mysqli_connect_errno())
    {
        printf("connection failed : %s\n",mysqli_connect_errno());
        exit();
    }
}


function emailcheck($email)
{
    global $mysqli , $safe_email , $check_res;
    $safe_email= mysqli_real_escape_string($mysqli,$email);
    $sql_query=  "SELECT `id` FROM subscribers WHERE email = '".$safe_email."'";
    $check_res = mysqli_query($mysqli, $sql_query) or die(mysqli_error($mysqli));

}

?>