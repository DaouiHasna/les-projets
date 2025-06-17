# RMI+SSL Password Manager with Swing GUI

## Overview

This project implements a secure, encrypted password manager service using **Java RMI over SSL/TLS**. Both the server and client feature **Swing-based graphical user interfaces**. All passwords are stored in plaintext on the server (so the client can retrieve and copy them), and **every remote method invocation is encrypted** because the server exports the remote object with SSL socket factories.

### Included Files

```
PasswordManager.java
PasswordManagerImpl.java
ServerUIInterface.java
ServerUI.java
ServerLauncher.java
ClientUIInterface.java
ClientUI.java
ClientLauncher.java
server-keystore.jks
client-truststore.jks
README.md
```

## Prerequisites

1. **Java 11 or higher** (Java 8 should also work with minor adjustments).  
2. **keytool** (part of the JDK) to generate keystores and truststores.  

## Keystore / Truststore Setup

The server needs a keystore (`server-keystore.jks`) containing its self-signed certificate. The client needs a truststore (`client-truststore.jks`) containing the server's public certificate.

Run these commands in a terminal (adjust passwords and distinguished name as needed):

```bash
# 1) Generate server keystore with a self-signed certificate:
keytool -genkeypair \
    -alias serverkey \
    -keyalg RSA -keysize 2048 \
    -keystore server-keystore.jks \
    -dname "CN=RmiPasswordServer, OU=Dev, O=Example, L=City, ST=State, C=US" \
    -keypass serverpass \
    -storepass serverpass \
    -validity 365

# 2) Export the server's public certificate:
keytool -exportcert \
    -alias serverkey \
    -keystore server-keystore.jks \
    -file server-cert.cer \
    -rfc \
    -storepass serverpass

# 3) Create client truststore and import that certificate:
keytool -importcert \
    -alias serverkey \
    -file server-cert.cer \
    -keystore client-truststore.jks \
    -storepass clientpass \
    -noprompt
```

After these steps, you will have:

- `server-keystore.jks`
- `server-cert.cer`
- `client-truststore.jks`

Copy `server-keystore.jks` and `client-truststore.jks` into this project directory.

## How to Compile

In a terminal, navigate to this directory and run:

```bash
javac PasswordManager.java PasswordManagerImpl.java ServerUIInterface.java ServerUI.java ServerLauncher.java ClientUIInterface.java ClientUI.java ClientLauncher.java
```

You should see no compilation errors.

## How to Run the Server

```bash
java ServerLauncher
```

- A Swing window titled **"Password Manager RMI Server"** will appear.  
- Click **"Start RMI Server"**.  
- The server console (text area) will display:
  ```
  SSL properties set.
  RMI registry created on port 1099.
  PasswordManagerService bound. Server is ready (SSL enabled).
  ```

The server is now listening for RMI calls on port 1099 over SSL/TLS.

## How to Run the Client

In a separate terminal (in the same directory), run:

```bash
java ClientLauncher
```

- If the server is running, a Swing window titled **"Password Manager RMI Client"** will appear.  
- Use the login panel to **register** a new user or **log in** as an existing user.  
- Once logged in, the main user panel allows you to:
  - **Add Service** (store a new service name + password)  
  - **Get Password** (retrieve & copy a stored password)  
  - **Change Password** (update an existing password)  
  - **Delete Service** (remove a service)  
  - **List Services** (view all stored services)  
  - **Logout** (return to the login screen)  

All operations invoke remote methods on the server stub; each RPC is transparently encrypted via SSL/TLS.

## Project Structure

```
rmi_password_manager_swing/
├── PasswordManager.java
├── PasswordManagerImpl.java
├── ServerUIInterface.java
├── ServerUI.java
├── ServerLauncher.java
├── ClientUIInterface.java
├── ClientUI.java
├── ClientLauncher.java
├── server-keystore.jks
├── client-truststore.jks
└── README.md
```

### Files Description

- **PasswordManager.java**: RMI remote interface.  
- **PasswordManagerImpl.java**: Server-side implementation. Exported with SSL factories.  
- **ServerUIInterface.java**: Interface for logging messages in the server’s Swing UI.  
- **ServerUI.java**: Swing UI to start the RMI server and display logs.  
- **ServerLauncher.java**: Launches the ServerUI.  
- **ClientUIInterface.java**: Interface for displaying messages in the client’s Swing UI.  
- **ClientUI.java**: Swing UI for user login and password management.  
- **ClientLauncher.java**: Sets SSL truststore, looks up RMI stub, launches the ClientUI.  
- **server-keystore.jks**: Server’s keystore (contains private key + self-signed cert).  
- **client-truststore.jks**: Client’s truststore (trusts server’s cert).  
- **README.md**: This file.

## Security Notes

- All stored passwords (master and service) are **plaintext** on the server.  
- All remote calls (register, authenticate, add, get, etc.) are transported over **SSL/TLS** because the remote implementation is exported with `SslRMIClientSocketFactory` and `SslRMIServerSocketFactory`.  
- In a production system, consider hashing or encrypting stored passwords and using a proper CA-signed certificate instead of a self-signed certificate.

Enjoy using this secure RMI-based Password Manager with a friendly Swing UI!
