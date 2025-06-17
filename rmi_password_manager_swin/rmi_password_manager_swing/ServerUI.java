import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.rmi.registry.LocateRegistry;
import java.rmi.registry.Registry;

/**
 * A Swing‐based UI for launching the RMI+SSL PasswordManager service
 * and viewing real‐time log messages from PasswordManagerImpl.
 */
public class ServerUI extends JFrame implements ServerUIInterface {

    private final JTextArea logArea;
    private final JButton startButton;

    public ServerUI() {
        setTitle("Password Manager RMI Server");
        setSize(600, 400);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        // Layout: vertical BoxLayout
        Box box = Box.createVerticalBox();
        add(box);

        // Title label
        JLabel titleLbl = new JLabel("Password Manager RMI+SSL Server");
        titleLbl.setFont(new Font("Arial", Font.BOLD, 16));
        titleLbl.setAlignmentX(Component.CENTER_ALIGNMENT);
        box.add(Box.createVerticalStrut(10));
        box.add(titleLbl);
        box.add(Box.createVerticalStrut(10));

        // Start button
        startButton = new JButton("Start RMI Server");
        startButton.setAlignmentX(Component.CENTER_ALIGNMENT);
        box.add(startButton);
        box.add(Box.createVerticalStrut(10));

        // Log area
        logArea = new JTextArea();
        logArea.setEditable(false);
        JScrollPane scrollPane = new JScrollPane(logArea);
        box.add(scrollPane);

        // Action: When “Start RMI Server” is clicked
        startButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                startButton.setEnabled(false);
                new Thread(() -> startRMIServer()).start();
            }
        });
    }

    /**
     * Append a log message (thread‐safe).
     */
    @Override
    public void log(String message) {
        SwingUtilities.invokeLater(() -> {
            logArea.append(message + "\n");
            logArea.setCaretPosition(logArea.getDocument().getLength());
        });
    }

    /**
     * Starts the RMI registry and binds the PasswordManagerImpl.
     */
    private void startRMIServer() {
        try {
            // 1) Configure SSL keystore/truststore system properties
            System.setProperty("javax.net.ssl.keyStore", "server-keystore.jks");
            System.setProperty("javax.net.ssl.keyStorePassword", "serverpass");
            System.setProperty("javax.net.ssl.trustStore", "server-keystore.jks");
            System.setProperty("javax.net.ssl.trustStorePassword", "serverpass");

            log("SSL properties set.");

            // 2) Create a plain RMI registry on port 1099
            Registry registry = LocateRegistry.createRegistry(1099);
            log("RMI registry created on port 1099.");

            // 3) Instantiate and export the PasswordManagerImpl (with SSL factories)
            PasswordManagerImpl.setLogger(this);
            PasswordManagerImpl managerImpl = new PasswordManagerImpl();
            registry.rebind("PasswordManagerService", managerImpl);
            log("PasswordManagerService bound. Server is ready (SSL enabled).");

        } catch (Exception ex) {
            log("Server exception: " + ex.getMessage());
            ex.printStackTrace();
        }
    }
}
