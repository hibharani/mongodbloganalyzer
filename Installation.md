# Introduction #

To use this application we have to install the symfony mongodb logger plugin inside our symfony project. This will allow us to start collecting logs.

Then we have to install the LogAnalyzer application

# Prerequisites: #

You neeed to install MongoDB as explained here: http://www.mongodb.org/display/DOCS/Getting+Started

Basically is just three steps:

  * Download the binaries
  * Uncompress the files
  * Create the /data/db folder
  * Launch the server bin/mongod run

After that Install the MongoDB PHP Driver from PECL. See instructions here: http://www.mongodb.org/display/DOCS/Installing+the+PHP+Driver

# Get the Source Code: #

```
svn checkout http://mongodbloganalyzer.googlecode.com/svn/trunk/ mongodbloganalyzer-read-only
```

# sfMongoDBLoggerPlugin installation: #

Copy the sfMongoDBLoggerPlugin folder inside your plugins folder in symfony 1.2 or 1.3.

Then in the factories.yml edit the following configuration (in this example for the dev environment)

```
  logger:
   class: sfAggregateLogger
   param:
     level: debug
     loggers:
       sf_web_debug:
         class: sfWebDebugLogger
         param:
           level: debug
           condition:       %SF_WEB_DEBUG%
           xdebug_logging:  true
           web_debug_class: sfWebDebug
       sf_file_debug:
         class: sfMongoDBLogger
         param:
           level: debug
```

# LogAnalyzer Installation: #

After you downloaded the code, create a virtual host pointing to this folder:

```
/path/to/dowload/loganalyzer/web
```

Here's an example host file:

```
<VirtualHost *:80>
  ServerName sf20.al
  DocumentRoot "/Work/sf20/web"
  DirectoryIndex index.php
  CustomLog  "/Work/sf20/access.log" combined
  <Directory "/Work/sf20/web">
    AllowOverride All
    Allow from All
  </Directory>
</VirtualHost>
```

Modify this to fit your needs.

After you restarted Apache, be sure to launch the mongod process as explained here: http://www.mongodb.org/display/DOCS/Getting+Started