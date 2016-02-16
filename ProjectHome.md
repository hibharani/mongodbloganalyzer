This project is an example on how to use the sfRequestHandler component from the syfmony framework.

Here you will find a MongoDBLogger that is used to centralize application logs in one place. So if you run an application across several servers you can have the logs in one place, the MongoDB database. More information here: http://obvioushints.blogspot.com/2009/07/my-guess-on-symfony-2.html .

Then the we have a standalone web application that can connect to the MongoDB database server. This application can be used to search and filter through the logs in a web interface similar of webgrind.