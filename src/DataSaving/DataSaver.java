package DataSaving;

import org.json.JSONObject;

import javax.net.ssl.SSLSocket;
import javax.net.ssl.SSLSocketFactory;
import java.io.*;
import java.util.ArrayList;

public class DataSaver {
    private String address;
    private Integer port;
    private PrintWriter out;

    /**
     * DataSaver constructor
     *
     * @param dataserverAddress
     * @param dataserverPort
     */
    public DataSaver(String dataserverAddress, Integer dataserverPort)
    {
        this.address = dataserverAddress;
        this.port = dataserverPort;
    }

    /**
     * Starts a secure SSL connection with specified server
     *
     * @return bool
     * @throws Exception
     */
    public boolean connect() throws Exception {
        try {
            SSLSocketFactory factory = (SSLSocketFactory) SSLSocketFactory.getDefault();
            SSLSocket socket = (SSLSocket) factory.createSocket(this.address, this.port);

            socket.startHandshake();

            PrintWriter out = new PrintWriter(new BufferedWriter(new OutputStreamWriter(socket.getOutputStream())));

            this.out = out;
        } catch (Exception ex) {
            System.out.println(ex.getMessage());
            return true;
        }

        return false;
    }

    /**
     * Sends JSON to the dataserver
     *
     * @param json
     * @return
     * @throws Exception
     */
    public boolean sendJson(ArrayList<JSONObject> json) throws Exception
    {
        try {
            out.println(json.get(0));
            out.println();
            out.flush();

            if (out.checkError())
                System.out.println("Something went wrong with sending data to server");

        } catch (Exception e) {

            return false;
        }

        return true;
    }
}
