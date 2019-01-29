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
        LinkedBlockingQueue<JSONObject> XMLQueue = new LinkedBlockingQueue<>();
//        DataSaver dataServer = new DataSaver("52.166.192.171", 443);
        DataSaver dataServer = new DataSaver("192.168.1.21", 443);

        Boolean error = dataServer.connectToDataServer();
//        Boolean error = false;
        QueueThread queueThread = new QueueThread(XMLQueue, dataServer);
        queueThread.start();

        if (!error) {
            try (
                    //Socket the generator is on
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
}
