<!--
    Messing with PHP web pages as part of building a portfolio for employment to show I am capable of learning these things.
    This page was build on 6/13/2019 by Robert De La Cruz

    Credits go to:
    hugohabel's answer on https://stackoverflow.com/questions/11927260/printing-contents-of-array-on-separate-lines
    Dark-Reaper-'s answer on https://stackoverflow.com/questions/1678010/php-server-on-local-machine/21872484#21872484
    user1864610's answer on https://stackoverflow.com/questions/28160604/inserting-a-list-of-filenames-from-a-folder-into-mysql

    To run the PHP test server, go to the command line and enter the following two commands:
    cd path/to/your/app
    php -S localhost:8000

    This exercise was to learn how to gather folder contents from the file system, ideally to be saved to a file, manually copy/pasted to, or (ideally) automatically added to a mysql database, which can be later retrieved as automatically generated hyperlinks. (A kind of self-filling gallery web page)
-->
<html>
<head></head>
<body>

    <h1>My first PHP page</h1>
    
    <p>When run on the localhost, this page displays the current date and all the file names (full path) in C:/</p>

    <p>Hello World!  Today is <?php echo date('l, F jS, Y'); ?>.</p>
    
    <?php
        $dir = "file:///C:/";

        // Sort in ascending order - this is default
        $a = scandir($dir);

        //print_r($a);

        foreach ($a as $item) {
            echo $dir . $item . "<br/>";
        }
    ?>

</body>

</html>