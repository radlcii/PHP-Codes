<!--
    Messing with PHP web pages as part of building a portfolio for employment to show I am capable of learning these things.
    This page was build on 6/14/2019 by Robert De La Cruz

    Credits go to:
    Javascript, HTML form and the PHP code to load the file into the database by vincy@phppot.com; https://phppot.com/php/import-csv-file-into-mysql-using-php/
    As well as the sources on previous versions of this page, and many other sources that Google has turned up for me.
    Special shout out to StackOverflow, where Google tends to send me.

    This is another code to use PHP's connection with HTML to automatically generate html hyperlinks.  
    This version uses a jquery/javascript and html form to choose a csv file to add to the database
    I'll include a csv file in the GitHub repo consisting of the URLs and file extensions of images 
    from my own profile at Imgur.com, screenshots from the game Guild Wars 2

    The csv file's name on my GitHub repo is zz-ImgurImageURLs.csv, though you can use your own.
    
    To run the PHP test server, go to the command line and enter the following two commands:
    cd path/to/your/app
    php -S localhost:8000

-->
<html>
<head>
    <link rel="icon" href="data:,">                                             <!-- Resolving a favicon issue that appeared from nowhere -->

    <script type="text/javascript">                                             // This code is what makes the form work from the local machine, something that PHP is not meant to do.  Found at https://phppot.com/php/import-csv-file-into-mysql-using-php/
        $(document).ready(
        function() {
	    $("#frmCSVImport").on( "submit",
	    function() {

		$("#response").attr("class", "");
		$("#response").html("");
		var fileType = ".csv";
		var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+("
				+ fileType + ")$");
		if (!regex.test($("#file").val().toLowerCase())) {
			$("#response").addClass("error");
			$("#response").addClass("display-block");
			$("#response").html(
					"Invalid File. Upload : <b>" + fileType
							+ "</b> Files.");
			return false;
		    }
		    return true;
	    	});
	    });
    </script>
    <style>
        * {
            text-align:center;
            color:gold;
        }
        body{
            background-color:black;
        }
        form {
            border: solid gold 1px;
        }
        button {
            color:black;
        }
    </style>
</head>
<body>
    <h1>Just another PHP page</h1>
    <p> 
        This is another code to use PHP's connection with HTML to automatically generate html hyperlinks from a database table of image URLs as strings. <br>
        This version uses an uploaded csv file, the one provided on my GitHub contains a selection of images from my own profile at Imgur.com, <br>
        which are just random scenery screenshots from the game Guild Wars 2.
    </p>

    <form class="form-horizontal" action="" method="post" name="uploadCSV" enctype="multipart/form-data">   <!-- This is the HTML form to upload the csv file.  Found at https://phppot.com/php/import-csv-file-into-mysql-using-php/ -->
    <div class="input-row">
        <label class="col-md-4 control-label">Choose CSV File</label> <input
            type="file" name="file" id="file" accept=".csv">
        <button type="submit" id="submit" name="import"
            class="btn-submit">Import</button>
        <br />
    </div>
    <div id="labelError"></div>
    </form>

    <?php
        error_reporting(E_ALL);                                                 // This is ugly for a client to see but I'm the dev making a silly program.
        ini_set('display_errors', 1);                                           // These would probably be removed or at least commented out prior to shipping

        $host="localhost";                                                      // Variables for the SQL connection ($conn)
        $user="newb";                                                           // Your MySQL user name (I am definitely the newb)
        $password="";                                                           // Your MySQL password
        $dbname="php_db_test";                                                  // Your MySQL Schema/DB name here
        $port=3306;                                                             // Your MySQL port number
        $socket="mysql";

        $conn = new mysqli($host, $user, $password, $dbname, $port, $socket);   // This makes the actual connection

        $conn->query( "DROP TABLE IF EXISTS php_db_test.ImgurImages;" );        // Remove previous version of table if one exists
        $conn->query( "CREATE DATABASE IF NOT EXISTS php_db_test;" );           // Create the database if it doesn't exist 
        $conn->query( "CREATE TABLE IF NOT EXISTS php_db_test.ImgurImages (     /* Create the table if one does not exist (Which it should not) */
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            fileURL varchar(100) NOT NULL, 
            fileExt varchar(10) NOT NULL 
            );"
        );

        if (isset($_POST["import"])) {                                          // This code block takes the file and reads it into the database. Original found at https://phppot.com/php/import-csv-file-into-mysql-using-php/
            $fileName = $_FILES["file"]["tmp_name"];                            // This creates a temporary file in the directory of your PHP code, so don't be alarmed by a file with no extension appearing.
            if ($_FILES["file"]["size"] > 0) {
                $file = fopen($fileName, "r");
                while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                    if (strcmp($column[0], "fileURL") == 0) {                   // This was added to exclude with the first line of the csv file which is just column names
                        continue;
                    } else {
                        $sqlInsert = "INSERT INTO php_db_test.ImgurImages (fileURL,fileExt) values ('" . $column[0] . "','" . $column[1] . "')";
                    }
                    $result = mysqli_query($conn, $sqlInsert);
                    if (! empty($result)) {
                        $type = "success";
                        $message = "CSV Data Imported into the Database";
                    } else {
                        $type = "error";
                        $message = "Problem in Importing CSV Data";
                    }
                }
            }
        }

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