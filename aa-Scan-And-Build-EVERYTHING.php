<!--
    This document is one of a pair written by Robert De La Cruz II
    This code is not optimized and was never intended to for use in real applications.
    It's purpose is to provide a simple search form that allows a user to query a database filled with various media files,
        In doing so it provided a structure to learn how to use MySQL statements through PHP.  
    It is the "backside of the front end" code that proves the database was designed in a usable way.  
    The web page generated by this file should only be able to query the database, not update or change it.

    The database is created by a PHP file system iterator that simply takes everything in the directories starting from 
        a folder below the sight's root (because of permission issues) and recursively iterate down the file system
        while loading some media and web file types into the database.  As of writing this I doubt this is feasible (allowed)
        on a server device not owned or operated by me.
    The "website" setup has an index page in the root directory, but this file and its DB builder mate were required by the 
        testing system to be one folder below the root.

    The database used was a MySQL database, verification done on MySQL Workbench
    Microsoft's IIS software in Windows 10 was used to simulate a server to test this code.
        The database and server were both running from the same machine when testing this code.
        It was run successfully on a local network, successfully generating pages on several devices.
        The code was only tested in Mozilla Firefox, but should run in Internet Explorer and Microsoft Edge as well
    
    General Database Design:
        There is 1 table per general file type, e.g. JPGs and PNGs appear in the same IMAGES table, video files in another table
    
        Files are loaded into the database by their relative path, file extension and file name which are parsed from the path. 
            (Only the path/URL is really needed, the rest is arguably wasteful)
        
        Assumption:  Because this was developed on a local network using IIS to act as the server software:
            The relative path on the server machine will correspond to the address bar in a web browser

        Assumption:  Any file with a thumbnail will have the same file name, save for the file extension.  This thumbnail 
            path is stored in each row.
        
        Image thumbnailURLs are identical to their own fileURL 
            This is unnecessary and a touch wasteful as implemented, but could potentially save initail load times if a smaller 
                version of the original image is used as the thumbnail.
-->
<html>

<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="/aa-TestPageFilterSortStyles.css">     <!-- Stylesheet path goes here -->
    <link rel="icon" href="data:,">
    <title> DIRECTORY_BUILDER </title>
</head>

<body>
    <!-- The button that rebuilds everything is set off to the side because it took several minutes to run and was often unnecessary for testing -->
    <form method="post" style="text-align:left;" id="theTop">
        <input type="submit" name="rebuild" id="test" value="Rebuild the whole DB" onclick="return confirm('Are you sure you want to rebuild the whole smash?\nThis could take several minutes.')" />
    </form>

    <h1>Directory Builder Page</h1>
    <a href="/"> Back to Index </a>
    <br><br>

    <!-- The "form" consisting only of buttons with hard coded functions -->
    <div>
        <form method="post">
            <input type="submit" name="queryAll"        id="test"   value="Query EVERYTHING"    style="float:center;" /> <!-- Returns a list of text links to every single item in the database -->
        </form>
        <br>
        <form method="post">
            <input type="submit" name="rebuildSWF"      id="test"   value="Rebuild all SWFs"    style="float:left;" />
            <input type="submit" name="rebuildMP4"      id="test"   value="Rebuild all MP4s"    style="float:center;" />
            <input type="submit" name="rebuildMP3"      id="test"   value="Rebuild all MUSIC"   style="float:right;" />
        </form>
        <form method="post">
            <input type="submit" name="rebuildImages"   id="test"   value="Rebuild all IMGs"    style="float:left;" />
            <input type="submit" name="rebuildPHP"      id="test"   value="Rebuild all PHPs"    style="float:center;" />
            <input type="submit" name="rebuildHTML"     id="test"   value="Rebuild all HTMLs"   style="float:right;" />
        </form>
        <br>
        <form method="post">
            <input type="submit" name="clear"           id="test"   value="Clear Results"       style="float:center;" />
        </form>
    </div>

    <!-- Error reporting code -->
    <?php
        #error_reporting(E_ALL);
        #ini_set('display_errors', 1);
    ?>

    <!--
        The "main function" that runs when any button in the form is pressed.
        It simply takes the value of the button and runs it through a series of if-statements to figure out how to proceed
    -->
    <?php
        $db_name = "PHP_TEST_DB";           // Your database name here
        $ACTUAL_ROOT_DIRECTORY = "";        // Your root folder name here.  In testing the browser considered this the local network's IP address but this needs to be the actual file system folder NAME (not path)
        $fileArr = array();
        // Query all items in the database, returns a potentially very large spam of text lines to the browser window
        if(array_key_exists('queryAll',$_POST)){
            echo "Pulling Fucking EVERYTHING" . "<br>";
            queryWholeDB($db_name, "");
        }

        /*  All the following statements work in the same way: 
            They destroy the DB table pertaining to the input and rebuild an empty one,
            Call the file iterator to crawl the directory for files of the given type
            Rebuild the rows of the table
            Then finally display the results of the file type being worked with */
        if(array_key_exists('rebuildMP4',$_POST)){
            echo "Getting the videos" . "<br>";
            createTable ($db_name, "VIDEOS");
            $fileArr = getRelativePath(findByExt("mp4"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            queryWholeDB($db_name, "mp4");
        }
        if(array_key_exists('rebuildImages',$_POST)){
            echo "Pulling all Images" . "<br>";
            createTable ($db_name, "IMAGES");
            $fileArr = getRelativePath(findByExt("gif"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            $fileArr = getRelativePath(findByExt("jpg"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            $fileArr = getRelativePath(findByExt("png"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            queryWholeDB($db_name, "gif");
            queryWholeDB($db_name, "png");
            queryWholeDB($db_name, "jpg");
        }
        if(array_key_exists('rebuildSWF',$_POST)){
            echo "Pulling Any flash objects that exist..." . "<br>";
            createTable ($db_name, "FLASH");
            $fileArr = getRelativePath(findByExt("swf"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            queryWholeDB($db_name, "swf");
        }
        if(array_key_exists('rebuildMP3',$_POST)){
            echo "Grabbing the music!" . "<br>";
            createTable ($db_name, "MUSIC");
            $fileArr = getRelativePath(findByExt("mp3"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            queryWholeDB($db_name, "mp3");
        }
        if(array_key_exists('rebuildPHP',$_POST)){
            echo "Finding PHP files" . "<br>";
            createTable ($db_name, "PHP");
            $fileArr = getRelativePath(findByExt("php"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            queryWholeDB($db_name, "php");
        }
        if(array_key_exists('rebuildHTML',$_POST)){
            echo "Searching for HTML web pages" . "<br>";
            createTable ($db_name, "HTML");
            $fileArr = getRelativePath(findByExt("html"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            queryWholeDB($db_name, "html");
        }

        /*
        This does the same as above, but with everything in one go. 
        It takes several minutes to run, during which time the page will be unresponsive.
        I need to research some method of optimizing this process and trying to run it without locking the user out from using page.
        */
        if(array_key_exists('rebuild',$_POST)){
            echo "Dropping and recreating each table" . "<br>";
            createTable ($db_name, "IMAGES");
            createTable ($db_name, "VIDEOS");
            createTable ($db_name, "FLASH");
            createTable ($db_name, "MUSIC");
            createTable ($db_name, "HTML");
            createTable ($db_name, "PHP");

            #echo "Begin SQL operations on " . "GIFs" . "<br>";
            $fileArr = getRelativePath(findByExt("gif"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            
            #echo "Begin SQL operations on " . "JPGs" . "<br>";
            $fileArr = getRelativePath(findByExt("jpg"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);
            
            #echo "Begin SQL operations on " . "PNGs" . "<br>";
            $fileArr = getRelativePath(findByExt("png"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);

            #echo "Begin SQL operations on " . "MP4s" . "<br>";
            $fileArr = getRelativePath(findByExt("mp4"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);

            #echo "Begin SQL operations on " . "SWFs" . "<br>";
            $fileArr = getRelativePath(findByExt("swf"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);

            #echo "Begin SQL operations on " . "MP3s" . "<br>";
            $fileArr = getRelativePath(findByExt("mp3"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);

            #echo "Begin SQL operations on " . "PHPs" . "<br>";
            $fileArr = getRelativePath(findByExt("php"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);

            #echo "Begin SQL operations on " . "HTMLs" . "<br>";
            $fileArr = getRelativePath(findByExt("html"),$ACTUAL_ROOT_DIRECTORY);
            rebuildDB($fileArr, $db_name);

            echo "Finished all operations." . "<br>";
        }
    ?>

    <!--
        Takes the file array sent from the if-statement results above and the database name
        Creates a MySQL connection object and begins looping through the file array, inserting each item into the database.
        Each item type has been hard coded to be entered a specific way (Though they are basically identical)
        The connection is closed at the end of the operation, meaning a connection is opened and closed multiple times
            when rebuilding the whole database.
     -->
    <?php function rebuildDB ($fileArr, $db_name) {
            $NOT_FOUND = "";  // Default image to be used as a thumbnail or video poster if one can't be found
            $folder_name = "";
            $fileTitle = "";
            $fileURL = "";
            $fileExt = "";
            $thumbnailURL = "";

            $conn = connectSQL($db_name);

            foreach ($fileArr as $file) {
                $fileURL = $file;
                $fileTitle = pathinfo($file, PATHINFO_FILENAME);
                $fileExt = pathinfo($file, PATHINFO_EXTENSION);
                $folder_name = basename(dirname($file,1));

                #IF FILE IS IMAGE
                if($fileExt == "png" || $fileExt == "jpg" || $fileExt == "gif") {
                    $table_name = "IMAGES";
                    $thumbnailURL = $fileURL;
                    insertToTable ($conn, $table_name, $folder_name, $fileTitle, $fileURL, $fileExt, $thumbnailURL);
                }
                #IF FILE IS VIDEO
                if($fileExt == "mp4") {
                    $table_name = "videos";
                    $thumbnailURL = buildThumbnail($conn, $db_name, $fileTitle, $NOT_FOUND);
                    insertToTable ($conn, $table_name, $folder_name, $fileTitle, $fileURL, $fileExt, $thumbnailURL);
                }
                #IF FILE IS SWF
                if($fileExt == "swf") {
                    $table_name = "flash";
                    $thumbnailURL = buildThumbnail($conn, $db_name, $fileTitle, $NOT_FOUND);
                    insertToTable ($conn, $table_name, $folder_name, $fileTitle, $fileURL, $fileExt, $thumbnailURL);
                }
                #IF FILE IS MP3
                if($fileExt == "mp3") {
                    $table_name = "music";
                    $thumbnailURL = "";     // Image file to use as a thumbnail (e.g. Album art)
                    insertToTable ($conn, $table_name, $folder_name, $fileTitle, $fileURL, $fileExt, $thumbnailURL);
                }
                #IF FILE IS PHP
                if($fileExt == "php") {
                    $table_name = "php";
                    $thumbnailURL = "";     // Image file to use as a thumbnail
                    insertToTable ($conn, $table_name, $folder_name, $fileTitle, $fileURL, $fileExt, $thumbnailURL);
                }
                #IF FILE IS HTML
                if($fileExt == "html") {
                    $table_name = "html";
                    $thumbnailURL = "";     // Image file to use as a thumbnail
                    insertToTable ($conn, $table_name, $folder_name, $fileTitle, $fileURL, $fileExt, $thumbnailURL);
                }
            }
            /* Always close the SQL connection when done with it */
            closeSQL ($conn);
        }
    ?>

    <!-- This function blindly queries everything in the database.  It's only use is as a check to make sure data was actually entered and the DB can be queried.-->
    <?php function queryWholeDB($db_name, $type) {
            /* Establish a connection */
            $conn = connectSQL($db_name);
            /* Get all the tables */
            $result = $conn->query("show tables");
            /* Loop through all the tables, grab all rows in all tables */
            foreach ($result as $table) {
                $table = basename(implode("/", $table));
                $result = $conn->query("SELECT * FROM `{$table}`;" );
                
                /* Parse each row individually, and display it as a clickable link in the browser */
                if ($result->num_rows > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($type == "" || $type == $row['fileExt']) {
                            echo"<a href=\"../" . $row['fileURL'] . "\" >
                                ".$row['fileTitle']. "." .$row['fileExt']."
                                </a>" . "<br>";
                            echo $row['fileTags'] . "<br>";
                        }
                    }
                }
            }
            /* Always close the SQL connection when done with it. */
            closeSQL($conn);
        }
    ?>

    <!-- This function queries the image table against the item currently being added.  If there is a match, the image URL/path is returned to be added to the DB as a thumbnailURL -->
    <?php function buildThumbnail($conn, $db_name, $thumbTitle, $NOT_FOUND) {
            $sql = "SELECT fileURL FROM IMAGES WHERE fileTitle LIKE '{$thumbTitle}';";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                printf("Error message: %s<br>", $conn->error);
                echo $thumbTitle . "<br>";
            }
            else {
                mysqli_stmt_prepare($stmt, $sql);
                @mysqli_stmt_bind_param($stmt);
                mysqli_stmt_execute($stmt);
                $row = mysqli_stmt_get_result($stmt);
                $value = mysqli_fetch_array($row);
                $result = $value['fileURL'];
            }
            if (empty($result)){
                echo " Thumbnail not found for " . $thumbTitle . "<br>";    // Lets you know if a thumbnail was not found.  Simplest cause, no image file matching name of video/swf
                return $NOT_FOUND;
            }
            return $result;
    }?>

    <!-- This method does the actual insertion, and nothing more -->
    <?php function insertToTable ($conn, $table_name, $folder_name, $fileTitle, $fileURL, $fileExt, $thumbnailURL) {
            $conn->query(
                "INSERT INTO `{$table_name}` (fileFolderName, fileTitle, fileURL, fileExt, thumbnailURL)
                VALUES ('". $folder_name ."', '". $fileTitle ."', '". $fileURL ."', '". $fileExt ."', '". $thumbnailURL ."');"
            );
        }
    ?>

    <!-- The connection method -->
    <?php function connectSQL($db_name) {
            $host="localhost";          /* Database location, tested on a localhosted DB */
            $user="newb";               /* DB username, I am the newb */
            $password="";               /* DB password, none used when tested */
            $port=3306;                 /* DB port number, tested on port 3306 of the localhost */
            $socket="mysql";            /* Tells the system what kind of DB to use */
            $conn = new mysqli($host, $user, $password, $db_name, $port, $socket);
            return $conn;
        }
    ?>

    <!-- Closes the connection -->
    <?php function closeSQL ($conn) {
            $conn->close();
        }
    ?>

    <!-- Destroys the table by name, then makes a new one (of the smae name) to refresh the DB -->
    <?php function createTable($db_name, $table_name) {
            $conn = connectSQL($db_name);
            echo "Making Table " . $table_name . "<br>";
            $conn->query("DROP TABLE IF EXISTS {$table_name};");
            $conn->query(
                "CREATE TABLE IF NOT EXISTS {$table_name} (
                    fileFolderName varchar(100) NOT NULL,
                    fileTitle varchar(100) NOT NULL,
                    fileURL varchar(500) NOT NULL PRIMARY KEY, 
                    fileExt varchar(5) NOT NULL, 
                    thumbnailURL varchar(500), 
                    fileTags varchar(1024), 
                    fileDescription varchar(500)
                );"
            );
            closeSQL($conn);
        }
    ?>

    <!-- Helper function gotten from StackOverflow. This uses RegEx to help the directory iterator only choose the files of the specified type -->
    <?php function findByExt ($type) {
        $reg = '/^.+\.'.$type.'$/i';
        $Directory = new RecursiveDirectoryIterator(realpath('.'));
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator($Iterator, $reg, RecursiveRegexIterator::GET_MATCH);
        return $Regex;
    }?>

    <!-- Helper function that takes the file name that comes from the RegEx result and turns it into a working path -->
    <?php function getRelativePath($Regex, $ACTUAL_ROOT_DIRECTORY) {
            $resultArr = array();
            foreach ($Regex as $thing) {
                $path = implode($thing);
                $pathArr = explode(DIRECTORY_SEPARATOR, $path);
                $current = $pathArr[7];
                $isKeepGoing = true;
                while ($isKeepGoing) {
                    if ($pathArr[0]==$ACTUAL_ROOT_DIRECTORY) {
                        $isKeepGoing=false;
                    }
                    array_shift($pathArr);
                }
                $resultArr[] = implode("/", $pathArr);
            }
            return $resultArr;
        }
    ?>

    <!-- Modal for image display (Not necessary for this page) -->
    <div id="myModal" class="modal">
        <img class="myImg" id="img01">
    </div>
    <!-- Modal Script -->
    <script>
        var modal = document.getElementById("myModal");
        var i;

        var img = document.getElementsByClassName("myImg");
        var modalImg = document.getElementById("img01");

        for(i=0;i< img.length;i++) {    
            img[i].onclick = function(){

            modal.style.display = "block";
            modalImg.src = this.src;
            }
        }
        var span = document.getElementsByClassName("modal")[0];
        span.onclick = function() { 
            modal.style.display = "none";
        }
    </script>

    <script type="text/javascript">
        function AddTags(value)
        {
            
            var goButton = document.getElementById("go");
            goButton.click();
        }

        simulateLogin("testUser");
    </script>
    <!-- Padding for the bottom of the page -->
    <br><br><br>
</body> 

</html>