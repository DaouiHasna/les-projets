#!/usr/bin/env python3
"""
Script d'installation et de configuration automatique
"""

import os
import subprocess
import sys
from pathlib import Path

def check_python_version():
    """Vérifie la version de Python"""
    if sys.version_info < (3, 6):
        print("ERREUR: Python 3.6 ou supérieur requis")
        return False
    
    print(f"✓ Python {sys.version.split()[0]} détecté")
    return True

def install_dependencies():
    """Installe les dépendances Python"""
    print("Installation des dépendances...")
    try:
        subprocess.check_call([
            sys.executable, "-m", "pip", "install", "-r", "requirements.txt"
        ])
        print("✓ Dépendances installées avec succès")
        return True
    except subprocess.CalledProcessError:
        print("✗ Erreur lors de l'installation des dépendances")
        return False

def create_directory_structure():
    """Crée la structure de dossiers nécessaire"""
    directories = [
        "config",
        "scripts", 
        "templates",
        "logs"
    ]
    
    print("Création de la structure de dossiers...")
    for directory in directories:
        Path(directory).mkdir(exist_ok=True)
        print(f"✓ Dossier créé: {directory}/")
    
    return True

def copy_example_configs():
    """Copie les fichiers de configuration d'exemple"""
    print("Configuration des fichiers d'exemple...")
    
    # Vérifier si le fichier de config principal existe
    config_file = Path("config/vm-config.json")
    if not config_file.exists():
        print("⚠ Créez votre fichier config/vm-config.json à partir du template")
    else:
        print("✓ Fichier de configuration principal trouvé")
    
    return True

def run_tests():
    """Exécute les tests de base"""
    print("Test des imports...")
    try:
        import ssl
        import json
        from pyVim.connect import SmartConnect
        from pyVmomi import vim
        print("✓ Tous les modules requis sont disponibles")
        return True
    except ImportError as e:
        print(f"✗ Module manquant: {e}")
        return False

def main():
    """Fonction principale d'installation"""
    print("="*50)
    print("INSTALLATION DU SYSTÈME DE DÉPLOIEMENT VM")
    print("="*50)
    
    steps = [
        ("Vérification de Python", check_python_version),
        ("Création des dossiers", create_directory_structure),
        ("Installation des dépendances", install_dependencies),
        ("Configuration des exemples", copy_example_configs),
        ("Test des modules", run_tests)
    ]
    
    success_count = 0
    for step_name, step_func in steps:
        print(f"\n{step_name}...")
        if step_func():
            success_count += 1
        else:
            print(f"✗ Échec: {step_name}")
    
    print("\n" + "="*50)
    if success_count == len(steps):
        print("✓ INSTALLATION TERMINÉE AVEC SUCCÈS")
        print("\nÉtapes suivantes:")
        print("1. Éditez config/vm-config.json avec vos paramètres")
        print("2. Exécutez: python deploy-vm.py")
    else:
        print(f"✗ INSTALLATION PARTIELLE ({success_count}/{len(steps)} étapes réussies)")
    
    print("="*50)
if __name__ == "__main__":
    main()