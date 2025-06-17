import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.rmi.RemoteException;
import java.util.List;

/**
 * A Swing‐based client UI for interacting with the remote PasswordManager service over RMI+SSL.
 *
 * The UI has two “cards” (CardLayout):
 *   1) Login Panel
 *   2) Main Panel (where user can add/get/change/delete/list passwords)
 */
public class ClientUI extends JFrame implements ClientUIInterface {

    private final PasswordManager stub;

    private final CardLayout cardLayout = new CardLayout();
    private final JPanel mainPanel = new JPanel(cardLayout);

    // Login panel components
    private JTextField usernameField;
    private JPasswordField passwordField;

    // Main panel components
    private JLabel welcomeLabel;
    private JButton addButton, getButton, changeButton, deleteButton, listButton, logoutButton;

    /**
     * Constructor.  Expects an RMI stub already looked up.
     */
    public ClientUI(PasswordManager stub) {
        this.stub = stub;

        setTitle("Password Manager RMI Client");
        setSize(500, 300);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);

        // Build both panels and add to mainPanel
        mainPanel.add(buildLoginPanel(), "LOGIN");
        mainPanel.add(buildUserPanel(), "USER");

        add(mainPanel);
        cardLayout.show(mainPanel, "LOGIN");
    }

    /**
     * Build the login panel.
     */
    private JPanel buildLoginPanel() {
        JPanel panel = new JPanel(new BorderLayout());

        JLabel title = new JLabel("Login to Password Manager", SwingConstants.CENTER);
        title.setFont(new Font("Arial", Font.BOLD, 16));
        panel.add(title, BorderLayout.NORTH);

        JPanel form = new JPanel(new GridLayout(3, 2, 5, 5));
        form.setBorder(BorderFactory.createEmptyBorder(20, 50, 20, 50));

        form.add(new JLabel("Username:"));
        usernameField = new JTextField();
        form.add(usernameField);

        form.add(new JLabel("Master Password:"));
        passwordField = new JPasswordField();
        form.add(passwordField);

        JButton loginButton = new JButton("Login");
        form.add(loginButton);

        JButton registerButton = new JButton("Register");
        form.add(registerButton);

        panel.add(form, BorderLayout.CENTER);

        // Action: Login
        loginButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                doLogin();
            }
        });

        // Action: Register
        registerButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                doRegister();
            }
        });

        return panel;
    }

    /**
     * Build the main user panel (after login).
     */
    private JPanel buildUserPanel() {
        JPanel panel = new JPanel(new BorderLayout());

        welcomeLabel = new JLabel("Welcome!", SwingConstants.CENTER);
        welcomeLabel.setFont(new Font("Arial", Font.BOLD, 16));
        panel.add(welcomeLabel, BorderLayout.NORTH);

        JPanel buttonsPanel = new JPanel(new GridLayout(3, 2, 10, 10));
        buttonsPanel.setBorder(BorderFactory.createEmptyBorder(20, 50, 20, 50));

        addButton = new JButton("Add Service");
        getButton = new JButton("Get Password");
        changeButton = new JButton("Change Password");
        deleteButton = new JButton("Delete Service");
        listButton = new JButton("List Services");
        logoutButton = new JButton("Logout");

        buttonsPanel.add(addButton);
        buttonsPanel.add(getButton);
        buttonsPanel.add(changeButton);
        buttonsPanel.add(deleteButton);
        buttonsPanel.add(listButton);
        buttonsPanel.add(logoutButton);

        panel.add(buttonsPanel, BorderLayout.CENTER);

        // Action Listeners for each button:
        addButton.addActionListener(e -> doAddService());
        getButton.addActionListener(e -> doGetPassword());
        changeButton.addActionListener(e -> doChangePassword());
        deleteButton.addActionListener(e -> doDeleteService());
        listButton.addActionListener(e -> doListServices());
        logoutButton.addActionListener(e -> doLogout());

        return panel;
    }

    /**
     * Attempt to register a new user by calling stub.registerUser(...)
     */
    private void doRegister() {
        String user = usernameField.getText().trim();
        String pass = new String(passwordField.getPassword()).trim();
        if (user.isEmpty() || pass.isEmpty()) {
            showMessage("Username and password cannot be empty.");
            return;
        }
        try {
            boolean ok = stub.registerUser(user, pass);
            if (ok) {
                showMessage("User \"" + user + "\" registered. You can now log in.");
            } else {
                showMessage("Registration failed: username already exists.");
            }
        } catch (RemoteException ex) {
            showMessage("Error during registration: " + ex.getMessage());
        }
    }

    /**
     * Attempt to log in by calling stub.authenticateUser(...)
     */
    private void doLogin() {
        String user = usernameField.getText().trim();
        String pass = new String(passwordField.getPassword()).trim();
        if (user.isEmpty() || pass.isEmpty()) {
            showMessage("Username and password cannot be empty.");
            return;
        }
        try {
            boolean ok = stub.authenticateUser(user, pass);
            if (ok) {
                welcomeLabel.setText("Logged in as: " + user);
                cardLayout.show(mainPanel, "USER");
            } else {
                showMessage("Login failed: invalid credentials.");
            }
        } catch (RemoteException ex) {
            showMessage("Error during login: " + ex.getMessage());
        }
    }

    /**
     * Add a service + password (calls stub.addPassword).
     */
    private void doAddService() {
        String user = usernameField.getText().trim();
        String service = JOptionPane.showInputDialog(this, "Service name:");
        if (service == null || service.trim().isEmpty()) {
            return;
        }
        String pwd = JOptionPane.showInputDialog(this, "Password for \"" + service + "\":");
        if (pwd == null || pwd.trim().isEmpty()) {
            return;
        }
        try {
            boolean ok = stub.addPassword(user, service.trim(), pwd.trim());
            if (ok) {
                showMessage("Service \"" + service + "\" added.");
            } else {
                showMessage("Failed to add: service may already exist.");
            }
        } catch (RemoteException ex) {
            showMessage("Error during addPassword: " + ex.getMessage());
        }
    }

    /**
     * Retrieve a password for a service (calls stub.getPassword).
     */
    private void doGetPassword() {
        String user = usernameField.getText().trim();
        String service = JOptionPane.showInputDialog(this, "Service name to retrieve:");
        if (service == null || service.trim().isEmpty()) {
            return;
        }
        try {
            String pwd = stub.getPassword(user, service.trim());
            if (pwd != null) {
                showMessage("Password for \"" + service + "\": " + pwd);
            } else {
                showMessage("Service not found.");
            }
        } catch (RemoteException ex) {
            showMessage("Error during getPassword: " + ex.getMessage());
        }
    }

    /**
     * Change a service password (calls stub.changePassword).
     */
    private void doChangePassword() {
        String user = usernameField.getText().trim();
        String service = JOptionPane.showInputDialog(this, "Service name to change:");
        if (service == null || service.trim().isEmpty()) {
            return;
        }
        String newPwd = JOptionPane.showInputDialog(this, "New password for \"" + service + "\":");
        if (newPwd == null || newPwd.trim().isEmpty()) {
            return;
        }
        try {
            boolean ok = stub.changePassword(user, service.trim(), newPwd.trim());
            if (ok) {
                showMessage("Password changed for \"" + service + "\".");
            } else {
                showMessage("Failed to change: service not found.");
            }
        } catch (RemoteException ex) {
            showMessage("Error during changePassword: " + ex.getMessage());
        }
    }

    /**
     * Delete a service (calls stub.deletePassword).
     */
    private void doDeleteService() {
        String user = usernameField.getText().trim();
        String service = JOptionPane.showInputDialog(this, "Service name to delete:");
        if (service == null || service.trim().isEmpty()) {
            return;
        }
        try {
            boolean ok = stub.deletePassword(user, service.trim());
            if (ok) {
                showMessage("Service \"" + service + "\" deleted.");
            } else {
                showMessage("Failed to delete: service not found.");
            }
        } catch (RemoteException ex) {
            showMessage("Error during deletePassword: " + ex.getMessage());
        }
    }

    /**
     * List all services (calls stub.listServices).
     */
    private void doListServices() {
        String user = usernameField.getText().trim();
        try {
            List<String> services = stub.listServices(user);
            if (services.isEmpty()) {
                showMessage("No services stored.");
            } else {
                showMessage("Services: " + services);
            }
        } catch (RemoteException ex) {
            showMessage("Error during listServices: " + ex.getMessage());
        }
    }

    /**
     * Logout (go back to login card).
     */
    private void doLogout() {
        usernameField.setText("");
        passwordField.setText("");
        cardLayout.show(mainPanel, "LOGIN");
    }

    /**
     * Display a message to the user (via a popup).
     */
    @Override
    public void showMessage(String message) {
        JOptionPane.showMessageDialog(this, message);
    }
}
