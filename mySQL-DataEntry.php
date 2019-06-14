<!--
    Messing with PHP web pages as part of building a portfolio for employment to show I am capable of learning these things.
    This page was build on 6/14/2019 by Robert De La Cruz

    Credits go to:
    hugohabel's answer on https://stackoverflow.com/questions/11927260/printing-contents-of-array-on-separate-lines
    Dark-Reaper-'s answer on https://stackoverflow.com/questions/1678010/php-server-on-local-machine/21872484#21872484
    user1864610's answer on https://stackoverflow.com/questions/28160604/inserting-a-list-of-filenames-from-a-folder-into-mysql
    php query code retrieved from https://www.tutorialrepublic.com/php-tutorial/php-mysql-insert-query.php
    Extention extractor found at https://stackoverflow.com/questions/173868/how-do-i-get-extract-a-file-extension-in-php
    MySQL user authentication issues answer found at https://stackoverflow.com/questions/50026939/php-mysqli-connect-authentication-method-unknown-to-the-client-caching-sha2-pa
    And many other sources that Google turned up for me.

    This exercise was to learn how to gather folder contents from the file system, automatically add to a mysql database, which will be later retrievable as a self-filling gallery of sorts.
    The plan for the next version is to directly return a page of functioning hyperlinks to the files it finds by wrapping the found addresses in hard coded html.
    
    To run the PHP test server, go to the command line and enter the following two commands:
    cd path/to/your/app
    php -S localhost:8000

-->
<html>
<head>
    <link rel="icon" href="data:,">                                             <!-- Resolving a favicon issue that appeared from nowhere -->
</head>
<body>
    <p> When run on the localhost, this page is designed to capture all the file 
        names in the local host and add them to a mysql database </p>
    <?php
        error_reporting(E_ALL);                                                 // This is ugly for a client to see but I'm the dev making a silly program.
        ini_set('display_errors', 1);

        $dir = "file:///C:/";                                                   // Use the C drive because it's a fairly universal file system address.
        $fileArr = scandir($dir);

        $host="localhost";                                                      // Variables for the SQL connection ($conn)
        $user="newb";
        $password="";
        $dbname="php_db_test";
        $port=3306;
        $socket="mysql";

        $conn = new mysqli($host, $user, $password, $dbname, $port, $socket);   // This makes the actual connection

        echo 'Success... ' . mysqli_get_host_info( $conn );                     // Unnecessary, but it proves the connection was made
 
        $conn->query( "DROP TABLE IF EXISTS php_db_test.Items;" );              // Remove previous version of table if one exists
        $conn->query( "CREATE DATABASE IF NOT EXISTS php_db_test;" );           // Create the database if it doesn't exist 
        $conn->query( "CREATE TABLE IF NOT EXISTS php_db_test.Items (           /* Create the table if one does not exist (Which it should not) */
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            filePath varchar(100) NOT NULL, 
            fileExt varchar(10) NOT NULL 
            );"
        );
        
        
        foreach ($fileArr as $file) {                                           // Loop over all the file names gathered above
            $path = $dir . $file;                                               // Combine the directory and file name to create the full path
            echo "<br/>" . $path;                                               // Print the full path
            $ext  = pathinfo($file, PATHINFO_EXTENSION);                        // Capture the file extension

            $conn->query("INSERT INTO php_db_test.Items (filepath, fileExt)     /* Insert them into the database */
                VALUES ('". $path ."', '". $ext ."'); "
            );
        }

        $conn->close();                                                         // Operation complete, close the connection
    ?>

</body>

</html>