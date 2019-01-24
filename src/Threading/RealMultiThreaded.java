package Threading;

import Parsing.XMLParser;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
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
        LinkedBlockingQueue<StringBuilder> XMLQueue = new LinkedBlockingQueue<>();

        try (
                ServerSocket serverSocket = new ServerSocket(7789);
                Socket       clientSocket = serverSocket.accept();
                BufferedReader in  = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
        ) {
            String xmlLine;
            StringBuilder XMLElement = new StringBuilder();

            //luister naar </weatherdata>
            //flikker dat in een queue
            while (true) {
                xmlLine = in.readLine();
                System.out.println(xmlLine);
                XMLElement.append(xmlLine);
                if (xmlLine.equals("</WEATHERDATA>")) {
                    //end xml element and add it to queue
                    XMLQueue.put(XMLElement);
                    XMLElement = new StringBuilder();
                    //maak threads aan die shit uit de queue halen en verwerkemn

                    threadPool.execute(new HandleRequestThread(XMLQueue.take()));
                }
            }
        } catch (IOException ex) {
            System.out.println(ex.getMessage());

            return;
        }
    }
}
