import javax.swing.*;

/**
 * Launcher for the server Swing UI.  
 * Ensures all UI work is done on the Swing Event Dispatch Thread.
 */
public class ServerLauncher {
    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            ServerUI ui = new ServerUI();
            ui.setVisible(true);
        });
    }
}
