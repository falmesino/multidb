<?php

    /**
     * ./page.php
     * Created by Falmesino Abdul Hamid(http://github.com/falmesino)
     * 
     * This is the page that will be shown to the user after successful login.
     * This page also displaying related content from the selected database.
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
     * Declare the required variables
     */
    $title = '';
    $body = '';
    $date = '';

    /**
     * Check the auth session
     */
    if(isset($_SESSION['AUTH']))
    {
        /*
        echo '<pre>';
        print_r($_SESSION['AUTH']);
        echo '</pre>';    
        */
        
        /**
         * Attempt to connect and retrieve data from `page` table in the selected database
         */
        $connect = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Unable to establish connection to MySQL Database server!');
        if($connect)
        {
            mysql_select_db($_SESSION['AUTH']['database'], $connect) or die('Unable to connect to <strong>' . $_SESSION['AUTH']['database'] . '</strong> database!');
            
            $sql = "SELECT * FROM `" . $_SESSION['AUTH']['database'] . "`.`page`";
            $qry = mysql_query($sql);
            $num = 0;
            $row = array();
            
            if($qry)
            {
                $num = mysql_num_rows($qry);
                if($num > 0)
                {
                    $row = mysql_fetch_array($qry);
                    
                    /*
                    echo '<pre>';
                    print_r($row);
                    echo '</pre>';
                    */
                    
                    $title = $row['title'];
                    $body = $row['body'];
                    $date = date('D, d F Y H:i:s A', strtotime($row['created']));
                    
                }
                else
                {
                    $messages[] = array(
                        'class' => 'warning',
                        'message' => 'There\'s no data to display!'
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
        /**
         * If fail, return user to login page
         */
        header('location:index.php');
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
                        
                        <?php
                            if(isset($messages)):
                                foreach($messages as $message):
                                    echo '<div class="alert alert-' . $message['class'] . '">';
                                    echo $message['message'];
                                    echo '</div><!--/ .alert -->';
                                endforeach;
                            endif;
                        ?>
                        
                        <table class="table table-bordered table-striped" style="margin-top: 32px;">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <strong>Database</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25%">DB Host</td>
                                    <td><?php echo DB_HOST; ?></td>
                                </tr>
                                <tr>
                                    <td>DB Username</td>
                                    <td><?php echo DB_USERNAME; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <strong>Account</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DB Name</td>
                                    <td><?php echo $_SESSION['AUTH']['database']; ?></td>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <td><?php echo $_SESSION['AUTH']['username']; ?></td>
                                </tr>
                                <tr>
                                    <td>Display Name</td>
                                    <td><?php echo $_SESSION['AUTH']['display_name']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h1><?php echo $title; ?></h1>
                        <p class="text-muted"><small><?php echo $date; ?></small></p>
                        <p><?php echo $body; ?></p>
                        
                        <a href="./logout.php" onclick="return confirm('End your session?');" class="btn btn-danger">Logout</a>
                        
                    </div><!--/ .col-xs-12 -->
                </div><!--/ .row -->
            </div><!--/ .container -->
        </section>
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
    </body>
</html>