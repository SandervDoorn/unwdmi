package Threading;

import DataSaving.DataSaver;
import org.json.JSONObject;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.LinkedBlockingQueue;


public class RealMultiThreaded {
    private static final ExecutorService threadPool = Executors.newCachedThreadPool();

    /**
     * @param args
     */
    public static void main (String[] args) throws Exception
    {
        System.out.println("Attempting to connect to server");
        LinkedBlockingQueue<JSONObject> JsonQueue = new LinkedBlockingQueue<>();
        DataSaver dataServer = new DataSaver("52.166.192.171", 443);

        Boolean error = dataServer.connect();

        QueueThread queueThread = new QueueThread(JsonQueue, dataServer);
        queueThread.start();

        if (!error) {
            System.out.println("Connection established, awaiting incoming data");
            try (
                    //Socket the generator is on
                    ServerSocket serverSocket = new ServerSocket(7789)
            ) {
                while (true) {
                    Socket clientSocket = serverSocket.accept();

                    threadPool.execute(new HandleRequestThread(clientSocket, JsonQueue));
                }

            } catch (IOException ex) {
                System.out.println(ex.getMessage());
            }
        }
    }
}
