import java.rmi.Remote;
import java.rmi.RemoteException;
import java.util.List;

/**
 * Remote interface for a simple password manager (RMI over SSL).
 * Stores all passwords in plaintext on the server so the client can retrieve them.
 * Network communication is encrypted because the implementation is exported with SSL socket factories.
 */
public interface PasswordManager extends Remote {

    /**
     * Register a new user with a master password (plaintext).
     * @param username       the username to register
     * @param masterPassword the plaintext master password
     * @return true if registration succeeded; false if username already exists
     * @throws RemoteException if a remote error occurs
     */
    boolean registerUser(String username, String masterPassword) throws RemoteException;

    /**
     * Authenticate an existing user against the stored master password.
     * @param username       the username
     * @param masterPassword the plaintext master password
     * @return true if authentication succeeds; false otherwise
     * @throws RemoteException if a remote error occurs
     */
    boolean authenticateUser(String username, String masterPassword) throws RemoteException;

    /**
     * Add a new service + password for a given user (stored plaintext).
     * @param username        the existing username
     * @param serviceName     the service name (e.g., "gmail")
     * @param servicePassword the plaintext password for that service
     * @return true if added successfully; false if the service already exists or user not found
     * @throws RemoteException if a remote error occurs
     */
    boolean addPassword(String username, String serviceName, String servicePassword) throws RemoteException;

    /**
     * Retrieve the plaintext password for a given service under a user.
     * @param username    the existing username
     * @param serviceName the service name
     * @return the plaintext service password, or null if user or service not found
     * @throws RemoteException if a remote error occurs
     */
    String getPassword(String username, String serviceName) throws RemoteException;

    /**
     * Change the stored password (plaintext) for a service.
     * @param username           the existing username
     * @param serviceName        the service to change
     * @param newServicePassword the new plaintext password
     * @return true if changed successfully; false if user or service not found
     * @throws RemoteException if a remote error occurs
     */
    boolean changePassword(String username, String serviceName, String newServicePassword) throws RemoteException;

    /**
     * Delete a service entry for the given user.
     * @param username    the existing username
     * @param serviceName the service to delete
     * @return true if deleted; false if user or service not found
     * @throws RemoteException if a remote error occurs
     */
    boolean deletePassword(String username, String serviceName) throws RemoteException;

    /**
     * List all service names stored for the given user.
     * @param username the existing username
     * @return a List of service names (empty if none or if user not found)
     * @throws RemoteException if a remote error occurs
     */
    List<String> listServices(String username) throws RemoteException;
}
