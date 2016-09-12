<?php

    /**
     * index.php
     * Created by Falmesino Abdul Hamid(http://github.com/falmesino)
     * 
     * User Account on multidb_a
     * Username: usera
     * Password: cimolbandung
     * 
     * User Account on multidb_b
     * Username: userb
     * Password: tahusumedang
     * 
     * Passwords are encoded using base64 method
     * 
     * MySQL connection settings are up to you.
     * For this example we're using same server with different database names.
     */

    /**
     * We plan to store the selected database inside session.
     * Start the session_start() before anything else.
     */
    session_start();

    /**
     * Include the configuration file
     */
    require_once('./config.php');

    /**
     * Store all messages in here to make your life easier
     * There's two parameter for this variable, they are:
     * String $class, the Bootstrap's alert message class for the message you want display.
     * String $message, the message itself.
     */
    $messages = array();

    /**
     * Login form's logic
     */
    if(isset($_POST['submit'])){
        
        /**
         * Reset the message storage variable
         */
        $messages = array();
        
        /**
         * Validation counter, if more than 0 the program should not advance.
         */
        $valid = 0;
        
        /**
         * Some light validation
         */
        
        if(empty($_POST['username'])){
            $messages[] = array(
                'class' => 'warning',
                'message' => 'Username is required!'
            );
            $valid++;
        }
        
        if(empty($_POST['password'])){
            $messages[] = array(
                'class' => 'warning',
                'message' => 'Password is required!'
            );
            $valid++;
        }
        
        if(empty($_POST['database'])){
            $messages[] = array(
                'class' => 'warning',
                'message' => 'Database is required!'
            );
            $valid++;
        }
        
        if($valid == 0)
        {
            $login_username = mysql_real_escape_string($_POST['username']);
            $login_password = base64_encode(mysql_real_escape_string($_POST['password']));
            $login_database = mysql_real_escape_string($_POST['database']);

            /**
             * Attempting to connect to selected database and find the user account
             * in that database
             */
            
            $connect = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Unable to establish connection to MySQL Database server!');
            if($connect)
            {
                mysql_select_db($login_database, $connect) or die('Unable to connect to <strong>' . $login_database . '</strong> database!');
                
                $sql = "SELECT * 
                        FROM `" . $login_database . "`.`users` 
                        WHERE 
                            `users`.`username` = '" . $login_username . "' AND 
                            `users`.`password` = '" . $login_password . "'";
                $qry = mysql_query($sql);
                $num = 0;
                $row = array();
                
                if($qry)
                {
                    $num = mysql_num_rows($qry);
                    if($num > 0)
                    {
                        $row = mysql_fetch_array($qry);
                        $messages[] = array(
                            'class' => 'success',
                            'message' => 'Welcome, <strong>' . $row['display_name'] . '</strong>! Redirecting you in 3 seconds...'
                        );
                        
                        /**
                         * Save authentication data into session
                         */
                        $_SESSION['AUTH'] = array(
                            'username' => $login_username,
                            'password' => $login_password,
                            'database' => $login_database,
                            'display_name' => $row['display_name']
                        );
                        
                        /**
                         * Redirect the user after 5 seconds to page.php
                         */
                        header( "refresh:3;url=page.php" );
                    }
                    else
                    {
                        $messages[] = array(
                            'class' => 'warning',
                            'message' => 'There\'s no user account associated with username <strong>' . $login_username . '</strong> on database <strong>' . $login_database . '</strong>!'
                        );
                    }
                }
                else
                {
                    $messages[] = array(
                        'class' => 'danger',
                        'message' => mysql_error()
                    );
                }
                
            }
            else
            {
                $messages[] = array(
                    'class' => 'danger',
                    'message' => 'Unable to establish connection to MySQL database server!'
                );
            }
            
            
        }
        else
        {
            $messages[] = array(
                'class' => 'danger',
                'message' => 'Please complete the form first!'
            );
        }
        
    }

?>

<!doctype html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <title>Multiple Database</title>
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body>
        
        <section class="">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <h1>Multiple Database Login Example</h1>
                        <form role="form" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" maxlength="16" placeholder="Username" required name="username">
                            </div><!--/ .form-group -->
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" maxlength="16" placeholder="Password" required name="password">
                            </div><!--/ .form-group -->
                            <div class="form-group">
                                <label>Database</label>
                                <select class="form-control" name="database" required>
                                    <option value="multidb_a">multidb_a</option>
                                    <option value="multidb_b">multidb_b</option>
                                </select>
                            </div><!--/ .form-group -->
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-block" value="Submit" name="submit">
                            </div><!--/ .form-group -->
                            <div class="form-group">
                                <?php
                                    if(isset($messages)):
                                        foreach($messages as $message):
                                            echo '<div class="alert alert-' . $message['class'] . '">';
                                            echo $message['message'];
                                            echo '</div><!--/ .alert -->';
                                        endforeach;
                                    endif;
                                ?>
                            </div><!--/ .form-group -->
                        </form>
                        <p class="text-muted text-center"><small>Created by <a href="http://github.com/falmesino" target="_blank">Falmesino Abdul Hamid</a></small></p>
                    </div><!--/ .col-xs-12 -->
                </div><!--/ .row -->
            </div><!--/ .container -->
        </section><!--/ . -->
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
    </body>
</html>