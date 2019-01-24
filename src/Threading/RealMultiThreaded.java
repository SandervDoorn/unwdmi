package Threading;

import DataSaving.DataSaver;
import org.json.JSONObject;

import java.io.FileWriter;
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
    public static void main (String[] args) throws Exception
    {
        LinkedBlockingQueue<JSONObject> XMLQueue = new LinkedBlockingQueue<>();
        DataSaver dataServer = new DataSaver("52.166.192.171", 443);

//        Boolean error = dataServer.connectToDataServer();
        Boolean error = false;

        if (!error) {
            try (
                    ServerSocket serverSocket = new ServerSocket(7789)
            ) {
                while (true) {
                    Socket clientSocket = serverSocket.accept();
                    threadPool.execute(new HandleRequestThread(clientSocket, XMLQueue));
                    if (XMLQueue.size() > 50) {
                        JSONObject json =  XMLQueue.take();

//                        try (FileWriter file = new FileWriter("/home/martin/Documents/dikkeshit.txt")) {
//                            System.out.println(json.toString());
//                            file.write(json.toString() + "\n");
//                        }

                        Boolean result = dataServer.sendJsonToDataServer(json);

                        if (!result) {
                            XMLQueue.put(json);
                        }
                    }
                }

            } catch (IOException ex) {
                System.out.println(ex.getMessage());

                return;
            }
        }
    }
}
