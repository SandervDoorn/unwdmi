package Threading;

import Parsing.XMLParser;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.net.Socket;

public class HandleRequestThread implements Runnable {
    private StringBuilder xml;

    /**
     * @param xml
     * @throws IOException
     */
    public HandleRequestThread(StringBuilder xml) throws IOException {
        this.xml = xml;
    }

    @Override
    public void run() {
        XMLParser parser = new XMLParser(this.xml);
        JSONObject parsedJson = parser.parseXML();

    }
}
