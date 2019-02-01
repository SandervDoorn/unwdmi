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
     * @param JsonQueue
     * @throws IOException
     */
    public HandleRequestThread(Socket clientSocket, LinkedBlockingQueue<JSONObject> JsonQueue) throws IOException {
        this.socket = clientSocket;
        this.in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
        this.queue = JsonQueue;
    }

    @Override
    public void run() {
        StringBuilder XMLElement = new StringBuilder();

        try {
            String xmlLine;

            //Listen to socket for incoming XML
            while (this.socket.isConnected()) {

                xmlLine = this.in.readLine();

                if (xmlLine != null) {

                    XMLElement.append(xmlLine);
                    if (xmlLine.equals("</WEATHERDATA>")) {
                        //We received the end of an XML element, parase it and add it to the queue
                        XMLParser XMLParser = new XMLParser(XMLElement.toString());
                        JSONObject result = new JSONObject();
                        try {
                            //end xml element and add it to queue
                            result = XMLParser.parseXML();
                        } catch (Exception ex) {
                            System.out.print(ex.getStackTrace().toString());
                        }

                        if (result != null && result.length() > 0) {
                            queue.put(result);
                        }

                        //Reset the variable
                        XMLElement = new StringBuilder();
                    }
                }
            }

        } catch (IOException | InterruptedException e) {
            e.printStackTrace();
        }
    }
}
