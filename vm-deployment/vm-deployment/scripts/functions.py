#!/usr/bin/env python3
"""
Fonctions utilitaires pour la gestion VMware
"""

import logging
import ssl
import time
from datetime import datetime
from pathlib import Path

try:
    from pyVim.connect import SmartConnect, Disconnect
    from pyVmomi import vim
    from pyVim.task import WaitForTask
except ImportError:
    print("ERREUR: pyVmomi non installé. Utilisez: pip install pyvmomi")
    exit(1)


def setup_logging():
    """Configure le système de logging"""
    # Création du dossier logs s'il n'existe pas
    log_dir = Path("logs")
    log_dir.mkdir(exist_ok=True)
    
    # Configuration du logger
    log_file = log_dir / f"deployment_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"
    
    logging.basicConfig(
        level=logging.INFO,
        format='%(asctime)s - %(levelname)s - %(message)s',
        handlers=[
            logging.FileHandler(log_file, encoding='utf-8'),
            logging.StreamHandler()
        ]
    )
    
    logger = logging.getLogger(_name_)
    logger.info(f"Logging initialisé - Fichier: {log_file}")
    return logger


def validate_config(config):
    """Valide la configuration chargée"""
    required_fields = {
        'vcenter': ['host', 'username', 'password'],
        'vm': ['name', 'template', 'datacenter', 'cpu', 'memory_mb']
    }
    
    try:
        for section, fields in required_fields.items():
            if section not in config:
                print(f"ERREUR: Section '{section}' manquante dans la configuration")
                return False
            
            for field in fields:
                if field not in config[section]:
                    print(f"ERREUR: Champ '{field}' manquant dans '{section}'")
                    return False
        
        # Validation des types
        vm_config = config['vm']
        if not isinstance(vm_config['cpu'], int) or vm_config['cpu'] <= 0:
            print("ERREUR: Le nombre de CPU doit être un entier positif")
            return False
        
        if not isinstance(vm_config['memory_mb'], int) or vm_config['memory_mb'] <= 0:
            print("ERREUR: La mémoire doit être un entier positif (en MB)")
            return False
        
        return True
        
    except Exception as e:
        print(f"ERREUR lors de la validation: {str(e)}")
        return False


class VMwareManager:
    """Gestionnaire des opérations VMware"""
    
    def _init_(self, host, user, password, port=443):
        self.host = host
        self.user = user
        self.password = password
        self.port = port
        self.si = None
        self.logger = logging.getLogger(_name_)
    
    def connect(self):
        """Établit la connexion à vCenter"""
        try:
            # Désactiver la vérification SSL pour les tests
            context = ssl.create_default_context()
            context.check_hostname = False
            context.verify_mode = ssl.CERT_NONE
            
            self.si = SmartConnect(
                host=self.host,
                user=self.user,
                pwd=self.password,
                port=int(self.port),
                sslContext=context
            )
            
            if self.si:
                self.logger.info(f"Connexion établie à {self.host}")
                return True
            else:
                self.logger.error("Échec de connexion")
                return False
                
        except Exception as e:
            self.logger.error(f"Erreur de connexion: {str(e)}")
            return False
    
    def disconnect(self):
        """Ferme la connexion à vCenter"""
        if self.si:
            try:
                Disconnect(self.si)
                self.logger.info("Déconnexion réussie")
            except Exception as e:
                self.logger.error(f"Erreur lors de la déconnexion: {str(e)}")
    
    def find_object(self, obj_type, name):
        """Trouve un objet par son nom"""
        try:
            content = self.si.RetrieveContent()
            container = content.viewManager.CreateContainerView(
                content.rootFolder, [obj_type], True
            )
            
            for obj in container.view:
                if obj.name == name:
                    container.Destroy()
                    return obj
            
            container.Destroy()
            return None
            
        except Exception as e:
            self.logger.error(f"Erreur lors de la recherche de {name}: {str(e)}")
            return None
    
    def find_template(self, template_name):
        """Trouve un template par son nom"""
        self.logger.info(f"Recherche du template: {template_name}")
        template = self.find_object(vim.VirtualMachine, template_name)
        
        if template and template.config.template:
            self.logger.info(f"Template trouvé: {template_name}")
            return template
        else:
            self.logger.error(f"Template non trouvé ou invalide: {template_name}")
            return None
    
    def find_datacenter(self, datacenter_name):
        """Trouve un datacenter par son nom"""
        self.logger.info(f"Recherche du datacenter: {datacenter_name}")
        datacenter = self.find_object(vim.Datacenter, datacenter_name)
        
        if datacenter:
            self.logger.info(f"Datacenter trouvé: {datacenter_name}")
        else:
            self.logger.error(f"Datacenter non trouvé: {datacenter_name}")
        
        return datacenter
    
    def find_datastore(self, datastore_name):
        """Trouve un datastore par son nom"""
        return self.find_object(vim.Datastore, datastore_name)
    
    def find_network(self, network_name):
        """Trouve un réseau par son nom"""
        return self.find_object(vim.Network, network_name)
    
    def create_clone_spec(self, vm_config):
        """Crée la spécification de clonage"""
        try:
            # Configuration de la VM
            config_spec = vim.vm.ConfigSpec()
            config_spec.numCPUs = vm_config['cpu']
            config_spec.memoryMB = vm_config['memory_mb']
            config_spec.cpuHotAddEnabled = True
            config_spec.memoryHotAddEnabled = True
            
            # Configuration du disque si spécifiée
            if 'disk_gb' in vm_config:
                disk_size_kb = vm_config['disk_gb'] * 1024 * 1024
                
                # Recherche du premier disque existant
                device_changes = []
                virtual_disk_device = None
                
                # Modification du disque existant
                disk_spec = vim.vm.device.VirtualDeviceSpec()
                disk_spec.operation = vim.vm.device.VirtualDeviceSpec.Operation.edit
                disk_spec.device = vim.vm.device.VirtualDisk()
                disk_spec.device.key = 2000
                disk_spec.device.capacityInKB = disk_size_kb
                
                device_changes.append(disk_spec)
                config_spec.deviceChange = device_changes
            
            # Spécification de relocalisation
            relocate_spec = vim.vm.RelocateSpec()
            
            # Datastore si spécifié
            if 'datastore' in vm_config:
                datastore = self.find_datastore(vm_config['datastore'])
                if datastore:
                    relocate_spec.datastore = datastore
                    self.logger.info(f"Datastore configuré: {vm_config['datastore']}")
            
            # Spécification de clonage
            clone_spec = vim.vm.CloneSpec()
            clone_spec.location = relocate_spec
            clone_spec.config = config_spec
            clone_spec.powerOn = False
            clone_spec.template = False
            
            self.logger.info(f"Spécification de clonage créée - CPU: {vm_config['cpu']}, RAM: {vm_config['memory_mb']}MB")
            return clone_spec
            
        except Exception as e:
            self.logger.error(f"Erreur lors de la création de la spec de clonage: {str(e)}")
            return None
    
    def clone_vm(self, template, vm_name, datacenter, clone_spec):
        """Clone une VM à partir d'un template"""
        try:
            self.logger.info(f"Début du clonage: {vm_name}")
            
            # Dossier de destination
            vm_folder = datacenter.vmFolder
            
            # Lancement de la tâche de clonage
            task = template.Clone(
                folder=vm_folder,
                name=vm_name,
                spec=clone_spec
            )
            
            self.logger.info("Tâche de clonage lancée, attente de la fin...")
            
            # Attendre la fin de la tâche
            result = WaitForTask(task)
            
            if task.info.state == vim.TaskInfo.State.success:
                self.logger.info(f"Clonage réussi: {vm_name}")
                return result
            else:
                self.logger.error(f"Échec du clonage: {task.info.error}")
                return None
                
        except Exception as e:
            self.logger.error(f"Erreur lors du clonage: {str(e)}")
            return None
    
    def power_on_vm(self, vm):
        """Démarre une VM"""
        try:
            if vm.runtime.powerState == vim.VirtualMachinePowerState.poweredOn:
                self.logger.info("La VM est déjà démarrée")
                return True
            
            task = vm.PowerOn()
            WaitForTask(task)
            
            if task.info.state == vim.TaskInfo.State.success:
                self.logger.info("VM démarrée avec succès")
                return True
            else:
                self.logger.error(f"Échec du démarrage: {task.info.error}")
                return False
                
        except Exception as e:
            self.logger.error(f"Erreur lors du démarrage: {str(e)}")
            return False
    
    def get_vm_info(self, vm):
        """Obtient les informations d'une VM"""
        try:
            info = {
                'name': vm.name,
                'power_state': vm.runtime.powerState,
                'cpu': vm.config.hardware.numCPU,
                'memory_mb': vm.config.hardware.memoryMB,
                'guest_os': vm.config.guestFullName,
                'ip_address': vm.guest.ipAddress if vm.guest.ipAddress else 'N/A'
            }
            return info
        except Exception as e:
            self.logger.error(f"Erreur lors de la récupération des infos VM: {str(e)}")
            return None