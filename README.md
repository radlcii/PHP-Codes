# PHP Code
Coding exercises in PHP

These codes will mostly focus on practicing interface techniques with databases, and eventually will grow to include front end operations such as forms.
The branches of this repo hold previous iterations that lead to what is currently on master.

The current version was built to be served from a localhosted database by Microsoft's IIS software over a local network.
A MySQL database was used, with MySQL Workbench specifically used to verify operations were being performed successfully.
The database and "Server" were located on the same device during development and testing.
    The server was able to successfully serve pages to other devices on the local network.

Database software and some kind of server software or emulator is needed to test this code properly.
Some program or system acting as a server (IIS in my case) needed to be set up because most browsers will detect that they are looking at a file system directly and will not allow some functions, such as hyperlinks, to work.  
Depending where in the file system this is placed and run, Windows 10 permissions can stop this from functioning properly so it is  best to stay away from trying to test this on user folders such as Music, Pictures, Documents, etc. (Probably a good thing)
