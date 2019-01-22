package Threading;

import Parsing.XMLParser;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;
import java.util.concurrent.LinkedBlockingQueue;

public class HandleRequestThread implements Runnable {
    private BufferedReader in;
    private Socket socket;
    private LinkedBlockingQueue<JSONObject> queue;

    /**
     * @param clientSocket
     * @param XMLQueue
     * @throws IOException
     */
    public HandleRequestThread(Socket clientSocket, LinkedBlockingQueue<JSONObject> XMLQueue) throws IOException {
        this.socket = clientSocket;
        this.in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
        this.queue = XMLQueue;
    }

    @Override
    public void run() {
        StringBuilder XMLElement = new StringBuilder();
        //TODO remove the test variable when done
        int test = 0;

        try {
            String xmlLine;
            while (this.socket.isConnected() && test < 10) {
                xmlLine = this.in.readLine();
                XMLElement.append(xmlLine);
                if (xmlLine.equals("</WEATHERDATA>")) {
                    XMLParser XMLParser = new XMLParser(XMLElement.toString());

                    //end xml element and add it to queue
                    queue.put(XMLParser.parseXML());
                    XMLElement = new StringBuilder();
                    test++;
                }
            }
            System.out.println(queue);
            System.exit(1);
        } catch (IOException | InterruptedException e) {
            e.printStackTrace();
        }
    }
}
