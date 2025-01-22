# To start the project, you need to install Docker and Docker Compose.
1.  After installing Docker and Docker Compose, you can run the following command to start the project:
```bash
./scan.sh
```
2.  The scan.sh script will build the Docker image and start the container with the web application.
3.  Navigate to application. Login with the following credentials:  
![img.png](img.png)
Username:  
```
admin
```  
Password:  
```
admin
```  

4. Change and/or create new user in sonarqube web application
5. Create local project and get sonarqube token in sonarqube web application
6. Copy .env.example to .env and set your sonarqube settings
7. Rerun scan.sh script and get the result in sonarqube web application
![img_1.png](img_1.png)