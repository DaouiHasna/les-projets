import logging
import time
from datetime import datetime
from pathlib import Path
import ssl
import json
import traceback

from pyVim.connect import SmartConnect, Disconnect
from pyVmomi import vim


def setup_logging(log_dir="logs"):
    Path(log_dir).mkdir(parents=True, exist_ok=True)
    log_filename = f"{log_dir}/deploy_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"

    logger = logging.getLogger(__name__)
    logger.setLevel(logging.DEBUG)

    formatter = logging.Formatter('%(asctime)s - %(levelname)s - %(message)s')

    file_handler = logging.FileHandler(log_filename, encoding='utf-8')
    file_handler.setLevel(logging.DEBUG)
    file_handler.setFormatter(formatter)
    logger.addHandler(file_handler)

    console_handler = logging.StreamHandler()
    console_handler.setLevel(logging.INFO)
    console_handler.setFormatter(formatter)
    logger.addHandler(console_handler)

    return logger


def validate_config(config):
    required_keys = ["vcenter", "vm"]
    for key in required_keys:
        if key not in config:
            return False
    return True


class VMwareManager:
    def __init__(self, host, user, password, port=443):
        self.host = host
        self.user = user
        self.password = password
        self.port = port
        self.si = None

    def connect(self):
        context = ssl._create_unverified_context()
        try:
            self.si = SmartConnect(
                host=self.host,
                user=self.user,
                pwd=self.password,
                port=self.port,
                sslContext=context
            )
            return True
        except Exception as e:
            print(f"Erreur de connexion: {e}")
            return False

    def disconnect(self):
        if self.si:
            Disconnect(self.si)

    def find_datacenter(self, name):
        content = self.si.RetrieveContent()
        for dc in content.rootFolder.childEntity:
            if isinstance(dc, vim.Datacenter) and dc.name == name:
                return dc
        return None

    def find_template(self, template_name):
        content = self.si.RetrieveContent()
        for datacenter in content.rootFolder.childEntity:
            vm_folder = datacenter.vmFolder
            for vm in vm_folder.childEntity:
                if hasattr(vm, "config") and vm.name == template_name and vm.config.template:
                    return vm
        return None

    def get_resource_pool(self, datacenter):
        host_folder = datacenter.hostFolder
        compute_resource = host_folder.childEntity[0]
        return compute_resource.resourcePool

    def create_clone_spec(self, resource_pool):
        relocate_spec = vim.vm.RelocateSpec()
        relocate_spec.pool = resource_pool

        clone_spec = vim.vm.CloneSpec(
            location=relocate_spec,
            powerOn=False,
            template=False
        )
        return clone_spec

    def clone_vm(self, template, vm_name, datacenter, clone_spec):
        vm_folder = datacenter.vmFolder
        try:
            task = template.Clone(folder=vm_folder, name=vm_name, spec=clone_spec)
            self.wait_for_task(task)
            return task.info.result
        except Exception as e:
            print("Erreur de clonage:")
            traceback.print_exc()
            return None

    def power_on_vm(self, vm):
        try:
            task = vm.PowerOn()
            self.wait_for_task(task)
            return True
        except Exception as e:
            print(f"Erreur de démarrage: {e}")
            return False

    def wait_for_task(self, task):
        while task.info.state in [vim.TaskInfo.State.running, vim.TaskInfo.State.queued]:
            time.sleep(1)


def main():
    logger = setup_logging()

    config = {
        "vcenter": {
            "host": "10.11.5.83",
            "user": "administrator@vsphere.local",
            "password": "Cyber@2025"
        },
        "vm": {
            "template_name": "template",
            "vm_name": "New-VM-Test",
            "datacenter_name": "Datacenter"
        }
    }

    if not validate_config(config):
        logger.error("Configuration invalide.")
        return

    manager = VMwareManager(
        host=config["vcenter"]["host"],
        user=config["vcenter"]["user"],
        password=config["vcenter"]["password"]
    )

    logger.info("Connexion à vCenter...")
    if not manager.connect():
        logger.error("Échec de la connexion à vCenter.")
        return

    try:
        logger.info("Recherche du datacenter...")
        datacenter = manager.find_datacenter(config["vm"]["datacenter_name"])
        if not datacenter:
            logger.error("Datacenter non trouvé.")
            return

        logger.info("Recherche du template...")
        template = manager.find_template(config["vm"]["template_name"])
        if not template:
            logger.error("Template non trouvé.")
            return

        logger.info("Recherche du resource pool...")
        resource_pool = manager.get_resource_pool(datacenter)
        if not resource_pool:
            logger.error("Resource pool non trouvé.")
            return

        logger.info("Création de la spécification de clonage...")
        clone_spec = manager.create_clone_spec(resource_pool)

        logger.info("Clonage de la VM...")
        new_vm = manager.clone_vm(template, config["vm"]["vm_name"], datacenter, clone_spec)
        if new_vm:
            logger.info(f"VM clonée avec succès: {new_vm.name}")
            logger.info("Démarrage de la VM...")
            if manager.power_on_vm(new_vm):
                logger.info("VM démarrée avec succès.")
            else:
                logger.error("Échec du démarrage de la VM.")
        else:
            logger.error("Échec du clonage de la VM.")
    finally:
        logger.info("Déconnexion de vCenter.")
        manager.disconnect()


if __name__ == "__main__":
    main()
