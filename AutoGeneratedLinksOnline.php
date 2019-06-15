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
    get_browser_name function retrieved from Francesco R's response at https://php.net/manual/en/function.get-browser.php
    And many other sources that Google turned up for me.


    This is another code to use PHP's connection with HTML to automatically generate html hyperlinks.  
    This version uses a selection of images, from my own profile at Imgur.com, gotten as screenshots from the game Guild Wars 2
    
    To run the PHP test server, go to the command line and enter the following two commands:
    cd path/to/your/app
    php -S localhost:8000

-->
<html>
<head>
    <link rel="icon" href="data:,">                                             <!-- Resolving a favicon issue that appeared from nowhere -->
</head>
<body>
    <p> 
        This is another code to use PHP's connection with HTML to automatically generate html hyperlinks.  
        This version uses a selection of images, from my own profile at Imgur.com, gotten as screenshots 
        from the game Guild Wars 2
    </p>
    <?php
        error_reporting(E_ALL);                                                 // This is ugly for a client to see but I'm the dev making a silly program.
        ini_set('display_errors', 1);                                           // These would probably be removed or at least commented out prior to shipping

        $host="localhost";                                                      // Variables for the SQL connection ($conn)
        $user="newb";                                                           // Your MySQL user name
        $password="";                                                           // Your MySQL password
        $dbname="php_db_test";                                                  // Your MySQL Schema/DB name here
        $port=3306;                                                             // Your MySQL port number
        $socket="mysql";

        $conn = new mysqli($host, $user, $password, $dbname, $port, $socket);   // This makes the actual connection

        echo '<br>Success... ' . mysqli_get_host_info( $conn ) . "<br>";        // Unnecessary, but it proves the connection was made
 
        $conn->query( "DROP TABLE IF EXISTS php_db_test.ImgurImages;" );        // Remove previous version of table if one exists
        $conn->query( "CREATE DATABASE IF NOT EXISTS php_db_test;" );           // Create the database if it doesn't exist 
        $conn->query( "CREATE TABLE IF NOT EXISTS php_db_test.ImgurImages (     /* Create the table if one does not exist (Which it should not) */
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            fileURL varchar(100) NOT NULL, 
            fileExt varchar(10) NOT NULL                                        /* All the extensions are .jpg in this list.  This is for possible future use. */
            );"
        );
        
        /* Yes, I know this is the ugly hard-coded and ad-hoc way of doing this. */
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/FgKH0Xe.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/2S91xdW.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/nn2DpeO.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/mSwTMer.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/KVOh60G.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/LpI7FOB.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/DNMgPGc.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/8UAwQeQ.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/cVGt5GN.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/RKMTx4Q.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/5zwtTZ6.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/Df4PQHm.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/nKXW8xm.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/iNKXsVh.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/LJg557E.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/RK8h6rJ.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/UYfp8wh.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/VhpufNu.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/oicN5Qe.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/rQvUuBb.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/hROfaPX.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/wMFOcv5.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/opQzvgM.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/4aXDApM.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/HinniaO.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/7zpJI86.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/CJeJFo4.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/wjWPeH4.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/6boshxw.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/4MxP4lM.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/h0W9lmk.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/7vo9BUO.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/VwOS5ot.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/Qyb0C6x.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/LNbaWwu.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/WAkfngf.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/x03prya.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/XeLsvcf.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/4x6IQDX.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/IO3inbH.jpg' , '.jpg');" );
        $conn->query( "INSERT INTO php_db_test.ImgurImages (fileURL, fileExt) VALUES ( 'https://i.imgur.com/qTpiVxN.jpg' , '.jpg');" );

        $result = $conn->query("SELECT * FROM php_db_test.ImgurImages;" );      // Query the Images table and return everything in the table
        if ($result->num_rows > 0) {                                            // Check that there is a result in the first place
            while ($row = mysqli_fetch_array($result)) {
                echo "<a href=\"" . $row['fileURL'] . "\" >
                        <img src=\"" . $row['fileURL'] . "\" 
                            alt=\"Thumbnail not available\" 
                            style=\"max-width:300px\">
                     </a>";                                                     // Generates a link to the file with a thumbnail image
            }
        } else {
            echo "<br>Zero rows returned.";                                     // Message to say nothing was found
        }

        $conn->close();                                                         // Operation complete, close the connection
    ?>

</body>

</html>