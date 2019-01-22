package Threading;

import java.io.IOException;
import java.net.*;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class RealMultiThreaded {
    private static final ExecutorService threadPool = Executors.newCachedThreadPool();

    public static void main (String[] args)
    {
        try (
                ServerSocket serverSocket = new ServerSocket(7789)
        ) {
            while (true) {
                Socket clientSocket = serverSocket.accept();
                threadPool.execute(new HandleRequestThread(clientSocket));
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());

            return;
        }
    }
}
