import java.rmi.RemoteException;
import java.rmi.server.UnicastRemoteObject;
import java.util.*;

/**
 * Implementation of PasswordManager.  Stores all passwords in plaintext, in memory.
 * The constructor exports this object using SSL‐enabled socket factories,
 * so that all RMI calls (registerUser, addPassword, getPassword, etc.) are
 * carried over TLS/SSL.
 *
 * A static ServerUIInterface logger can be set via setLogger(...) so that
 * ServerUI receives log messages.
 */
public class PasswordManagerImpl extends UnicastRemoteObject implements PasswordManager {

    // In‐memory store: username → masterPassword (plaintext)
    private final Map<String, String> userMasterPasswords;

    // In‐memory store: username → (serviceName → servicePassword (plaintext))
    private final Map<String, Map<String, String>> userServicePasswords;

    // Logger for Swing UI (may be null if not set)
    private static ServerUIInterface logger = null;

    /** Called by ServerUI to register itself as the log receiver. */
    public static void setLogger(ServerUIInterface uiLogger) {
        logger = uiLogger;
    }

    /**
     * Constructor.
     * We call super(0, SslRMIClientSocketFactory, SslRMIServerSocketFactory)
     * to ensure every remote invocation is carried over SSL/TLS.
     */
    protected PasswordManagerImpl() throws RemoteException {
        super(
            /* port = */ 0,
            new javax.rmi.ssl.SslRMIClientSocketFactory(),
            new javax.rmi.ssl.SslRMIServerSocketFactory()
        );
        userMasterPasswords = Collections.synchronizedMap(new HashMap<>());
        userServicePasswords = Collections.synchronizedMap(new HashMap<>());
    }

    @Override
    public synchronized boolean registerUser(String username, String masterPassword) throws RemoteException {
        if (userMasterPasswords.containsKey(username)) {
            if (logger != null) logger.log("[SERVER] registerUser FAILED (exists): " + username);
            return false; // already exists
        }
        userMasterPasswords.put(username, masterPassword);
        userServicePasswords.put(username, Collections.synchronizedMap(new HashMap<>()));
        if (logger != null) logger.log("[SERVER] Registered user: " + username);
        return true;
    }

    @Override
    public synchronized boolean authenticateUser(String username, String masterPassword) throws RemoteException {
        if (!userMasterPasswords.containsKey(username)) {
            if (logger != null) logger.log("[SERVER] authenticateUser FAILED (no user): " + username);
            return false;
        }
        boolean ok = userMasterPasswords.get(username).equals(masterPassword);
        if (logger != null) {
            logger.log(ok
                ? "[SERVER] Authenticated user: " + username
                : "[SERVER] Authentication FAILED for: " + username);
        }
        return ok;
    }

    @Override
    public synchronized boolean addPassword(String username, String serviceName, String servicePassword) throws RemoteException {
        if (!userMasterPasswords.containsKey(username)) {
            if (logger != null) logger.log("[SERVER] addPassword FAILED (no user): " + username);
            return false;
        }
        Map<String, String> services = userServicePasswords.get(username);
        if (services.containsKey(serviceName)) {
            if (logger != null) logger.log("[SERVER] addPassword FAILED (service exists): " + username + " → " + serviceName);
            return false; // service already exists
        }
        services.put(serviceName, servicePassword);
        if (logger != null) logger.log("[SERVER] [" + username + "] Added service: " + serviceName);
        return true;
    }

    @Override
    public synchronized String getPassword(String username, String serviceName) throws RemoteException {
        if (!userMasterPasswords.containsKey(username)) {
            if (logger != null) logger.log("[SERVER] getPassword FAILED (no user): " + username);
            return null;
        }
        Map<String, String> services = userServicePasswords.get(username);
        if (!services.containsKey(serviceName)) {
            if (logger != null) logger.log("[SERVER] getPassword FAILED (no service): " + username + " → " + serviceName);
            return null;
        }
        String pwd = services.get(serviceName);
        if (logger != null) logger.log("[SERVER] [" + username + "] Retrieved password for: " + serviceName);
        return pwd;
    }

    @Override
    public synchronized boolean changePassword(String username, String serviceName, String newServicePassword) throws RemoteException {
        if (!userMasterPasswords.containsKey(username)) {
            if (logger != null) logger.log("[SERVER] changePassword FAILED (no user): " + username);
            return false;
        }
        Map<String, String> services = userServicePasswords.get(username);
        if (!services.containsKey(serviceName)) {
            if (logger != null) logger.log("[SERVER] changePassword FAILED (no service): " + username + " → " + serviceName);
            return false; // no such service
        }
        services.put(serviceName, newServicePassword);
        if (logger != null) logger.log("[SERVER] [" + username + "] Changed password for: " + serviceName);
        return true;
    }

    @Override
    public synchronized boolean deletePassword(String username, String serviceName) throws RemoteException {
        if (!userMasterPasswords.containsKey(username)) {
            if (logger != null) logger.log("[SERVER] deletePassword FAILED (no user): " + username);
            return false;
        }
        Map<String, String> services = userServicePasswords.get(username);
        if (services.remove(serviceName) != null) {
            if (logger != null) logger.log("[SERVER] [" + username + "] Deleted service: " + serviceName);
            return true;
        }
        if (logger != null) logger.log("[SERVER] deletePassword FAILED (no service): " + username + " → " + serviceName);
        return false;
    }

    @Override
    public synchronized List<String> listServices(String username) throws RemoteException {
        if (!userMasterPasswords.containsKey(username)) {
            if (logger != null) logger.log("[SERVER] listServices FAILED (no user): " + username);
            return Collections.emptyList();
        }
        List<String> services = new ArrayList<>(userServicePasswords.get(username).keySet());
        if (logger != null) logger.log("[SERVER] [" + username + "] Listing services: " + services);
        return services;
    }
}

