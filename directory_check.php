<?php
//credentails for the vault
$vUser = $_ENV['VUSER'];
$vPass = $_ENV['VPASS'];
$vLoc = $_ENV['VLOC'];
$vdata = $_ENV['VDATA'];
$vdport = $_ENV['VDPORT'];

//Site checking credentials
$siteUser = $_ENV['SITEUSER'];
$sitePass = $_ENV['SITEPASS'];
$siteLoc = $_ENV['SITELOC'];
$siteDB = $_ENV['SITEDB'];
$sitePort = $_ENV['SITEPORT'];

//to use in_array in a multidimensional array
/* This Function was originally found on https://www.php.net/manual/en/function.in-array.php contributed by https://www.php.net/manual/en/function.in-array.php */

function in_multiarray($elem, $array)
{
    $top = sizeof($array) - 1;
    $bottom = 0;
    while ($bottom <= $top) {
        if ($array[$bottom] == $elem)
            return true;
        else
                if (is_array($array[$bottom]))
            if (in_multiarray($elem, ($array[$bottom])))
                return true;
        $bottom++;
    }
    return false;
}
//connect to vault
$vaultdb = mysqli_connect($vLoc, $vUser, $vPass, $vdata, $vdport);
//if connection fails
if (!$vaultdb) {
    die('Could not connect: ' . mysql_error());
}
//pull active users from vault database
$active_users_sql = "SELECT lower(email) FROM staff WHERE hr_status = 'A'";
$active_user_query = mysqli_query($vaultdb, $active_users_sql);
$active_users_results = mysqli_fetch_all($active_user_query);

//connect to website database
$site_db = mysqli_connect($siteLoc, $siteUser, $sitePass, $siteDB, $sitePort);
//if connection fails
if (!$site_db) {
    die('Could not connect: ' . mysql_error());
}
//pulls all "publish" posts IDs from the directory post type.
$posts_ID_directory_sql = "SELECT ID FROM psd_posts WHERE post_status = 'publish' AND post_type = 'directory'";
$posts_ID_directory_query = mysqli_query($site_db, $posts_ID_directory_sql);
$posts_ID_directory_results = mysqli_fetch_all($posts_ID_directory_query);
$email_to_ignore = array('camp@provo.edu', 'dixonattendance@provo.edu', 'thsattendance@provo.edu', 'phsattendance@provo.edu', 'ths-socialworkers@provo.edu');
foreach ($posts_ID_directory_results as $person) {

    //pulls all emails from the directory for publish posts in the directory post type.
    $site_directory_sql = "SELECT meta_value FROM psd_postmeta WHERE post_ID = '" . $person[0] . "' AND meta_value LIKE '%@provo.edu%'";
    $site_directory_query = mysqli_query($site_db, $site_directory_sql);
    $site_directory_results = mysqli_fetch_all($site_directory_query);

    //check if emails from database are in the vault directory as active
    foreach ($site_directory_results as $user_email) {
        $user_email = strtolower($user_email[0]);
        $user_email = str_replace(' ', '', $user_email);
        if (in_multiarray($user_email, $active_users_results)) {
            //it is in the database so do nothing
        } elseif (in_array($user_email, $email_to_ignore)) {
            //ignore this email
        } else {
            // Find the post ID associated with the bad email
            $post_id_sql = "SELECT post_id FROM psd_postmeta WHERE meta_value = '$user_email' AND meta_key = 'email'";
            $post_id_query = mysqli_query($site_db, $post_id_sql);
            $post_id_result = mysqli_fetch_assoc($post_id_query);
            if ($post_id_result) {
                $post_id = $post_id_result['post_id'];
                // Set the post status to "draft"
                $update_post_status_sql = "UPDATE psd_posts SET post_status = 'draft' WHERE ID = '$post_id'";
                mysqli_query($site_db, $update_post_status_sql);
                print "Post ID $post_id set to draft for email $user_email\n";
            }
        }
    }
}

//close connections
mysqli_close($vaultdb);
mysqli_close($site_db);
