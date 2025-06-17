#  Déploiement Automatisé de Machines Virtuelles dans vCenter

##  Description

Ce projet permet de **déployer automatiquement des machines virtuelles** (VMs) dans un environnement VMware vSphere à l'aide de scripts Python. Il réduit les interventions manuelles, évite les erreurs humaines, et standardise le processus de déploiement à partir de templates.

##  Objectif

> Automatiser le processus de création, configuration et mise en service de VMs dans vCenter.

---

##  Arborescence du Projet
```bash
vm-deployment/
├── config/
│ └── vm-config.json # Fichier de configuration principal
├── scripts/
│ ├── deploy-vm.py # Script principal de déploiement
│ └── functions.py # Fonctions utilitaires
├── templates/
│ └── vm-template-config.json # Configuration spécifique des templates
├── logs/
│ └── deployment.log # Fichiers de journalisation
├── requirements.txt # Bibliothèques Python nécessaires
├── install.py # Script d'installation automatisée
└── README.md # Documentation (ce fichier)

```


---

##  Prérequis

- Python **3.6 ou supérieur**
- Accès à un serveur **VMware vCenter**
- Droits suffisants pour créer des VMs
- Templates de VM existants dans vSphere
- Connexion réseau au vCenter

---

##  Installation

### 1. Cloner le projet
```bash
git clone https://github.com/ton-utilisateur/vm-deployment.git
cd vm-deployment
```



---

##  Prérequis

- Python **3.6 ou supérieur**
- Accès à un serveur **VMware vCenter**
- Droits suffisants pour créer des VMs
- Templates de VM existants dans vSphere
- Connexion réseau au vCenter

---

##  Installation

### 1. Cloner le projet
```bash
git clone https://github.com/ton-utilisateur/vm-deployment.git
cd vm-deployment
```


### 2. Lancer le script d'installation
```bash
python install.py
```
Ce script :

    Vérifie votre version de Python

    Installe les dépendances via requirements.txt

    Crée les dossiers nécessaires

    Vérifie la présence des fichiers de configuration



##  Utilisation
Déploiement d'une VM
```bash
python scripts/deploy-vm.py
```

## Étapes effectuées :
```bash
    Connexion sécurisée à vCenter

    Vérification des ressources

    Clonage de la VM

    Configuration (CPU, RAM, disque)

    Connexion réseau

    Démarrage (optionnel)

```
##  Logs
```bash
Tous les événements sont enregistrés dans le dossier logs/ avec des fichiers datés.

```
##  Auteurs
```bash
    Hasna Daoui

    Nana Diawara
```


# Gestionnaire de Mots de Passe Sécurisé

## Description

Ce projet est une application Java sécurisée permettant de gérer des mots de passe personnels via une architecture client-serveur basée sur RPC (Remote Procedure Call). Les données sensibles sont protégées grâce à un chiffrement AES-256, une authentification avec hachage SHA-256 salé, et une communication sécurisée via TLS 1.3. Le projet est conteneurisé avec Docker pour simplifier le déploiement et garantir l'isolation des composants.

## Fonctionnalités principales

- Authentification sécurisée des utilisateurs  
- Stockage chiffré des mots de passe (AES-256)  
- Communication client-serveur sécurisée via TLS 1.3  
- Interface graphique simple (JavaFX / Swing)  
- Conteneurisation Docker avec orchestration Docker Compose

## Prérequis

- Java JDK 17 ou supérieur  
- Docker & Docker Compose installés  
- OpenSSL (pour générer le keystore SSL)  
- Connexion internet pour télécharger les dépendances

## Installation, Déploiement et Structure du projet

### 1. Télécharger les bibliothèques Java

Exécute les commandes suivantes et place les fichiers JAR dans les dossiers `client/` et `server/` :

```bash
curl -O https://repo1.maven.org/maven2/com/google/code/gson/gson/2.10.1/gson-2.10.1.jar  
curl -O https://repo1.maven.org/maven2/org/xerial/sqlite-jdbc/3.42.0.0/sqlite-jdbc-3.42.0.0.jar
```

### 2. Générer le keystore SSL (PKCS12)

Utilise OpenSSL pour créer le certificat et le keystore. Exécute les commandes suivantes et place le fichier `keystore.p12` dans le dossier `server/` :

```bash
openssl req -x509 -newkey rsa:2048 -keyout server.key -out server.crt \
  -days 365 -nodes \
  -subj "/C=MA/ST=Taroudant/L=Kchachda/O=CyberSecurity/OU=LocalDev/CN=172.16.101.128"

openssl pkcs12 -export -inkey server.key -in server.crt \
  -out keystore.p12 -name server
```

### 3. Construire les images Docker

Depuis la racine du projet, lance la construction des images Docker :

```bash
docker-compose build
```

### 4. Démarrer les services

Pour lancer le serveur et le client, exécute :

```bash
docker-compose up
```

### 5. Arrêter les services

Pour arrêter proprement tous les conteneurs, utilise :

```bash
docker-compose down
```

### 6. Structure du projet

```plaintext
password-manager/
├── client/
│   ├── PasswordManagerClient.java
│   ├── Dockerfile.client
│   ├── gson-2.10.1.jar
│   └── sqlite-jdbc-3.42.0.0.jar
├── server/
│   ├── PasswordManagerServer.java
│   ├── Dockerfile.server
│   ├── keystore.p12
│   ├── gson-2.10.1.jar
│   └── sqlite-jdbc-3.42.0.0.jar
├── data/
├── docker-compose.yml
└── README.md
```

## Sécurité

* Les mots de passe utilisateurs sont hachés avec SHA-256 et salés avant stockage.
* Les mots de passe stockés sont chiffrés avec AES-256.
* Toutes les communications client-serveur sont chiffrées via TLS 1.3.
* La gestion des sessions est sécurisée et temporaire.

## Auteurs
```bash
* Hasna Daoui
* Nana Diawara

```


# Gestionnaire de Mots de Passe – Java RMI

## I.DESCRIPTION

### 1. Objectif

Ce projet est une application Java sécurisée permettant de gérer des mots de passe personnels via une architecture client-serveur basée sur RMI (Remote Method Invocation). 
Les données sensibles sont protégées grâce à un chiffrement AES-256, une authentification avec hachage SHA-256 salé,
 et une communication sécurisée via TLS 1.3.

### 2. Structure du projet

```
password-manager-rmi/
├── PasswordManagerRemote.java           # Interface RMI
├── BasicResponse.java                   # Réponse simple avec success/message
├── RegisterResponse.java                # Représente un compte utilisateur
├── LoginResponse.java                   # Ajoute le token de session
├── PasswordcResponse.java               # Ajoute le mot de passe déchiffré
├── Account.java                         # Compte utilisateur
├── AccountListResponse.java             # Liste des comptes
├── PasswordManagerRMIServer.java        # Serveur avec GUI
├── PasswordManagerRMIClient.java        # Client avec GUI
└── README_RMI.md                            # Ce guide
```
## 3.Prérequis

- Java JDK 17 ou supérieur   
- OpenSSL (pour générer le keystore SSL)  
- Connexion internet pour télécharger les dépendances

## II.Installation, Déploiement 

### 1. Télécharger les bibliothèques Java

Exécute les commandes suivantes et place les fichiers JAR dans les dossiers client/ et server/ :

```bash

curl -O https://repo1.maven.org/maven2/org/xerial/sqlite-jdbc/3.42.0.0/sqlite-jdbc-3.42.0.0.jar
```

###  2.Générer le keystore SSL (PKCS12)

Utilise OpenSSL pour créer le certificat et le keystore. Exécute les commandes suivantes et place le fichier keystore.p12 dans le dossier server/ :


```bash
openssl req -x509 -newkey rsa:2048 -keyout server.key -out server.crt \
  -days 365 -nodes \
  -subj "/C=MA/ST=Taroudant/L=Kchachda/O=CyberSecurity/OU=LocalDev/CN=172.16.101.128"

openssl pkcs12 -export -inkey server.key -in server.crt \
  -out keystore.p12 -name server

```
### 3. Compilation et Exécution

#### a) Compilation

```bash
javac -cp ".:sqlite-jdbc-3.42.0.0.jar" *.java
```

#### b) Lancement du Serveur

```bash
java  
  --add-opens=java.base/java.lang=ALL-UNNAMED \
  --add-opens=java.base/java.util=ALL-UNNAMED \
  --add-opens=java.rmi/sun.rmi.server=ALL-UNNAMED \
  -cp ".:sqlite-jdbc-3.42.0.0.jar" \
  PasswordManagerRMIServer

```

#### c) Lancement du Client

```bash
java   --add-opens=java.base/java.lang=ALL-UNNAMED
       --add-opens=java.base/java.util=ALL-UNNAMED  
	   --add-opens=java.rmi/sun.rmi.server=ALL-UNNAMED  
	   -cp ".:sqlite-jdbc-3.42.0.0.jar"   PasswordManagerClient
```

### III. Réseau et Sécurité

•	Port RMI Registry : 1099 - doit être ouvert
•	Ports RMI dynamiques : Java alloue automatiquement des ports pour les objets RMI


#### Configuration SSL

```java
System.setProperty("javax.net.ssl.keyStore", "keystore.jks");
System.setProperty("javax.net.ssl.keyStorePassword", "password");
System.setProperty("java.rmi.server.useCodebaseOnly", "true");
System.setProperty("javax.net.ssl.trustStore", "truststore.jks");
System.setProperty("javax.net.ssl.trustStorePassword", "motdepasse");
```

## IV. Utilisation de l'application

- **Serveur** : démarrage automatique du registre RMI, journalisation en temps réel.
- **Client** : interface pour s’authentifier, ajouter, modifier et supprimer des comptes.

### API RMI

- `BasicResponse`, `LoginResponse`, `PasswordResponse`, `AccountListResponse`, `Account`

### Sécurité

- AES-256 pour les mots de passe
- SHA-256 avec sel pour les utilisateurs
- Tokens de session
- Isolation des données par utilisateur
- SSL/TLS sécurisé

### Dépannage

| Erreur | Solution |
|--------|----------|
| `ClassNotFoundException` | Vérifier le classpath |
| `ConnectException` | Vérifier que le serveur tourne |
| `NotBoundException` | Vérifier le nom de service |
| `AccessControlException` | Vérifier les permissions Java |

### Monitoring

Le serveur affiche : nombre de connexions, temps de réponse, activités par utilisateur.

## V. Conclusion

Ce projet met en œuvre une application de gestion de mots de passe sécurisée en RMI, avec interface utilisateur et configuration SSL. 
Il combine modularité, sécurité, et simplicité d’utilisation dans un environnement distribué.

## Auteurs

* Hasna Daoui
* Nana Diawara





# Script Simple d'Installation de Kubernetes

**Auteures** : Hasna Daoui, Diawara Nana
**Projet 3-P2**

---

## Description

Dans un contexte où les volumes de données et la complexité applicative ne cessent de croître,
**Kubernetes** s’impose comme la solution d’orchestration incontournable pour les conteneurs.

Ce projet propose une solution **automatisée, évolutive et reproductible** pour déployer un cluster Kubernetes, intégrant :

* **Kubeflow** pour la gestion des workflows de Machine Learning (MLOps)
* **Rook Ceph** pour un stockage distribué et persistant

Objectif : simplifier la mise en place d’un environnement cloud-native robuste, sécurisé et prêt à l’usage.

---

## Fonctionnalités

* Installation automatique des dépendances (Docker, kubeadm, Containerd, etc.)
* Initialisation du nœud maître avec `kubeadm init`
* Génération d’un token pour les nœuds workers
* Installation de Calico (CNI réseau)
* Déploiement de Rook Ceph pour le stockage distribué
* Installation de Kubeflow pour le MLOps

---

## Structure du projet

```
├── installmaster.sh   # Script à exécuter sur le nœud master
├── installworker.sh   # Script à exécuter sur le(s) nœud(s) worker
```

---

## Prérequis

* Le nœud master doit être en **mode Bridge**
* Une partition de disque dédiée doit être ajoutée avant exécution

---

## Instructions d’exécution

### Sur le nœud **master** :

```bash
sudo nano installmaster.sh
sudo chmod +x installmaster.sh
./installmaster.sh
```

### Sur le(s) nœud(s) **worker** :

```bash
sudo nano installworker.sh
sudo chmod +x installworker.sh
./installworker.sh
```

---

## Dépannage – Erreurs courantes

### 1. Swap activé

**Symptôme :** `swap is enabled`
**Solution :**

```bash
sudo swapoff -a
sudo sed -i '/ swap / s/^/#/' /etc/fstab
```

---

### 2. Conflit de cgroup driver (Docker vs kubelet)

**Solution :**

```bash
cat <<EOF | sudo tee /etc/docker/daemon.json
{
  "exec-opts": ["native.cgroupdriver=systemd"]
}
EOF

sudo systemctl restart docker
sudo systemctl restart kubelet
```

---

### 3. Pods bloqués en Pending

**Cause :** CNI non installé
**Solution :**

```bash
kubectl apply -f https://docs.projectcalico.org/manifests/calico.yaml
```

---

### 4. Kubeflow – "Unable to fetch pipeline runs"

**Solution :**

```bash
kubectl logs -n kubeflow deployment/ml-pipeline-ui
kubectl get svc -n kubeflow
```

---

### 5. Rook Ceph – CrashLoopBackOff

**Cause possible :** Pas de disque libre détecté
**Solution :**

* Vérifier qu’un disque libre est disponible
* Examiner les logs :

```bash
kubectl -n rook-ceph logs <nom-du-pod>
```

---

### 6. Connexion refusée avec `kubectl port-forward`

**Solution :**

```bash
kubectl get pods -n kubeflow
kubectl port-forward svc/ml-pipeline-ui -n kubeflow 8080:80
```

---

## Conclusion

Ce projet permet le déploiement complet et automatisé d’une infrastructure cloud-native, combinant Kubernetes, Kubeflow et Rook Ceph.
Il facilite l’installation d’un environnement évolutif, sécurisé et adapté aux charges de travail intensives comme le Machine Learning.


