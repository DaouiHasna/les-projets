import javax.swing.*;
import java.rmi.registry.LocateRegistry;
import java.rmi.registry.Registry;

/**
 * The client launcher:
 *
 * 1) Sets SSL truststore system properties (so the RMI client trusts the server's cert).  
 * 2) Looks up the remote stub from the plain registry at localhost:1099.  
 * 3) Launches the Swing UI (ClientUI), passing it the stub.
 */
public class ClientLauncher {
    public static void main(String[] args) {
        try {
            // 1) Configure the SSL truststore so we trust the server's self‐signed cert:
            System.setProperty("javax.net.ssl.trustStore", "client-truststore.jks");
            System.setProperty("javax.net.ssl.trustStorePassword", "clientpass");

            // 2) Lookup the RMI registry (plaintext) and retrieve the stub (SSL-exported).
            Registry registry = LocateRegistry.getRegistry("localhost", 1099);
            PasswordManager stub = (PasswordManager) registry.lookup("PasswordManagerService");

            // 3) Launch Swing UI on the Event Dispatch Thread:
            SwingUtilities.invokeLater(() -> {
                ClientUI ui = new ClientUI(stub);
                ui.setVisible(true);
            });
        } catch (Exception ex) {
            ex.printStackTrace();
            JOptionPane.showMessageDialog(null, "Failed to connect to server: " + ex.getMessage());
        }
    }
}
