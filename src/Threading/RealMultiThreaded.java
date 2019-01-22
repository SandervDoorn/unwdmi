package Threading;

import org.json.JSONObject;

import java.io.IOException;
import java.net.*;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.LinkedBlockingQueue;

public class RealMultiThreaded {
    private static final ExecutorService threadPool = Executors.newCachedThreadPool();

    /**
     * @param args
     */
    public static void main (String[] args)
    {
        LinkedBlockingQueue<JSONObject> XMLQueue = new LinkedBlockingQueue<>();

        try (
                ServerSocket serverSocket = new ServerSocket(7789)
        ) {
            while (true) {
                Socket clientSocket = serverSocket.accept();
                threadPool.execute(new HandleRequestThread(clientSocket, XMLQueue));
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());

            return;
        }
    }
}
