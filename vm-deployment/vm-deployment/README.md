# Déploiement Automatisé de VM

## 📌 Prérequis
- VMware PowerCLI installé
- Accès à vCenter
- Un template VM prêt
- PowerShell ≥ 5

## 🚀 Utilisation
cd scripts
.\deploy-vm.ps1

## 🧪 Tests
- Testé avec 2 VM : WebServer-01 et WebServer-02
- Les IPs sont différentes et pré-configurées dans vm-config.json

## 🛠 Dépannage
- Erreur connexion vCenter : Vérifie le réseau et les identifiants
- Template non trouvé : Vérifie le nom dans l'inventaire vCenter
- Vérifie le log logs/deployment.log pour les détails
