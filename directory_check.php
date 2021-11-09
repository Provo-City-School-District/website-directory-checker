<?php
//include a file with database credentials
include('db.php');
//to use in_array in a multidimensional array
    /* This Function was originally found on https://www.php.net/manual/en/function.in-array.php contributed by https://www.php.net/manual/en/function.in-array.php */
function in_multiarray($elem, $array)
    {
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top) {
            if($array[$bottom] == $elem)
                return true;
            else
                if(is_array($array[$bottom]))
                    if(in_multiarray($elem, ($array[$bottom])))
                        return true;
            $bottom++;
        }
        return false;
    }
//connect to vault
$vaultdb = mysqli_connect($vLoc, $vUser, $vPass, $vdata);
    //if connection fails
    if (!$vaultdb) {
        die('Could not connect: ' . mysql_error());
}
//pull active users from vault database
$active_users_sql = "SELECT lower(email) FROM staff WHERE hr_status = 'A'";
$active_user_query = mysqli_query($vaultdb, $active_users_sql);
$active_users_results = mysqli_fetch_all($active_user_query);

//connect to website database
$site_db = mysqli_connect($siteLoc, $siteUser, $sitePass, $siteDB);
    //if connection fails
    if (!$site_db) {
        die('Could not connect: ' . mysql_error());
}
//pulls all "publish" posts IDs from the directory post type.
$posts_ID_directory_sql = "SELECT ID FROM psd_posts WHERE post_status = 'publish' AND post_type = 'directory'";
$posts_ID_directory_query = mysqli_query($site_db, $posts_ID_directory_sql);
$posts_ID_directory_results = mysqli_fetch_all($posts_ID_directory_query);
$email_to_ignore = array('camp@provo.edu', 'dixonattendance@provo.edu', 'thsattendance@provo.edu');
foreach($posts_ID_directory_results as $person){

    //pulls all emails from the directory for publish posts in the directory post type.
    $site_directory_sql = "SELECT meta_value FROM psd_postmeta WHERE post_ID = '".$person[0]."' AND meta_value LIKE '%@provo.edu%'";
    $site_directory_query = mysqli_query($site_db, $site_directory_sql);
    $site_directory_results = mysqli_fetch_all($site_directory_query);

    //check if emails from database are in the vault directory as active
    foreach ($site_directory_results as $user_email) {
        $bad_emails_report = fopen("directory_report.txt", "a") or die("unable to open file!");
        $user_email = strtolower($user_email[0]);
        $user_email = str_replace(' ', '', $user_email);
        if(in_multiarray($user_email,$active_users_results)) {
            //it is in the database so do nothing
            } elseif(in_array($user_email, $email_to_ignore)){
            //ignore this email
            } else {
                //echo $user_email." is not in the Vault as active.\n";
                $insert_email_to_report = $user_email . " is not in the Vault as Active.\n";
                echo $insert_email_to_report;
               //  echo $insert_email_to_report;
                fwrite($bad_emails_report, $insert_email_to_report);
                print "Data Written\n";
            }
        fclose($bad_emails_report);
    }
}

//close connections
mysqli_close($vaultdb);
mysqli_close($site_db);
?>
