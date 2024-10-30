# Containerizing a PHP App with MySQL and Deploying on Kubernetes: A Step-by-Step Guide 
Vidoe Tutorial is available at this link : [this link](https://youtu.be/oCnE_KVu6Q0?si=bAo-B0mltNO7ThX_)

In this blog post, we will walk through the process of containerizing a PHP application with a MySQL database and deploying it on Kubernetes. This guide assumes you have a basic understanding of Docker and Kubernetes.

## 1. Project Overview

Our goal is to deploy a PHP application that interacts with a MySQL database on a Kubernetes cluster. We will start by reviewing the project files and the structure. A README file is provided for deploying on CentOS, but we will customize it for Kubernetes.

## 2. Initial Setup

Before diving into the containerization process, we need to set up our Kubernetes cluster and configure `kubectl` to interact with it efficiently.

1. **Create a Kubernetes Cluster**: Follow the instructions for setting up a cluster (reference the appropriate cloud provider documentation).
2. **Configure kubectl**: Alias `kubectl` to `k` for convenience. Use the following command:
   ```bash
   alias k=kubectl
   ```

3. **Check Cluster Connection**: Ensure that your `kubectl` is connected to the cluster by running:
   ```bash
   k api-versions
   ```

## 3. Creating the Dockerfile

The next step involves creating a Dockerfile to containerize the PHP application. The Dockerfile should use PHP 7.4 with Apache and include the MySQL extensions.

**Dockerfile (Dockerfile)**:
```dockerfile
# Use PHP 7.4 with Apache as the base image
FROM php:7.4-apache

# Install MySQL extension
RUN docker-php-ext-install mysqli

# Copy application source code
COPY . /var/www/html

# Expose port 80
EXPOSE 80
```

Make sure to update the database connection strings in your application to point to the Kubernetes MySQL service.

## 4. Building and Pushing the Docker Image

With the Dockerfile in place, it's time to build the Docker image and push it to Docker Hub. Follow these steps:

1. **Build the Docker Image**:
   ```bash
   docker build -t yourusername/ecommweb:v6 .
   ```

2. **Push the Image to Docker Hub**:
   ```bash
   docker push yourusername/ecommweb:v6
   ```

This will allow Kubernetes to access the image for deployment.

## 5. Creating Kubernetes Deployment

Next, we will create a YAML file to define the deployment of our application in Kubernetes.

**Deployment YAML (website-deployment.yaml)**:
```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: ecommweb
spec:
  replicas: 1
  selector:
    matchLabels:
      app: ecommweb
  template:
    metadata:
      labels:
        app: ecommweb
    spec:
      containers:
      - name: ecommweb
        image: yourusername/ecommweb:v6
        ports:
        - containerPort: 80
```

Deploy the application with:
```bash
kubectl apply -f website-deployment.yaml
```

## 6. Setting Up the Database

Instead of creating a custom database image, we will use the official MariaDB image to set up our database.

1. **Pull the MariaDB Image**:
   ```bash
   docker pull mariadb
   ```

2. **Create the Database Initialization Script**: You can create a script to initialize the database, then include it in a Dockerfile.

**Database Dockerfile (database/Dockerfile)**:
```dockerfile
FROM mariadb
ENV MYSQL_DATABASE=yourdatabase
ENV MYSQL_ROOT_PASSWORD=yourpassword
```

3. **Build and Push the Database Image**:
   ```bash
   docker build -t yourusername/mariadb .
   docker push yourusername/mariadb
   ```

4. **Create a Database Deployment**:
**Database Deployment YAML (database-deployment.yaml)**:
```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: mariadb
spec:
  replicas: 1
  selector:
    matchLabels:
      app: mariadb
  template:
    metadata:
      labels:
        app: mariadb
    spec:
      containers:
      - name: mariadb
        image: yourusername/mariadb
        env:
        - name: MYSQL_DATABASE
          value: yourdatabase
        - name: MYSQL_ROOT_PASSWORD
          value: yourpassword
```

Deploy the database:
```bash
kubectl apply -f database-deployment.yaml
```

## 7. Configuring Kubernetes Services

To expose your application and database within the Kubernetes cluster, you need to create services.

**Service YAML (website-service.yaml)**:
```yaml
apiVersion: v1
kind: Service
metadata:
  name: ecommweb
spec:
  type: LoadBalancer
  ports:
  - port: 80
  selector:
    app: ecommweb
```

Deploy the service:
```bash
kubectl apply -f website-service.yaml
```

## 8. Troubleshooting Database Connections

If you encounter issues connecting the PHP application to the MySQL database, ensure that:

- The database service name is correctly referenced in your application.
- The environment variables are set properly in your deployment YAML.


