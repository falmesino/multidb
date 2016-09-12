<?php

    /**
     * ./logout.php
     * Created by Falmesino Abdul Hamid(http://github.com/falmesino)
     * 
     * Logging out logic will be stored in this file
     */

    /**
     * We plan to store the selected database inside session.
     * Start the session_start() before anything else.
     */
    session_start();

    /**
     * Unset the AUTH session
     */
    unset($_SESSION['AUTH']);
    
    /**
     * Destroy the session
     */
    session_destroy();

    /**
     * Redirect user to login page
     */
    header('location:index.php');

?>