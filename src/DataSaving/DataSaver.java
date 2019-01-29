package DataSaving;

import org.json.JSONObject;

import javax.net.ssl.SSLSocket;
import javax.net.ssl.SSLSocketFactory;
import java.io.*;

public class DataSaver {
    private String dataserverAddress;
    private Integer dataserverPort;
    private PrintWriter out;
    private SSLSocket socket;

    public DataSaver(String dataserverAddress, Integer dataserverPort)
    {
        this.dataserverAddress = dataserverAddress;
        this.dataserverPort = dataserverPort;
    }

    public boolean connectToDataServer() throws Exception {
        try {
            SSLSocketFactory factory =
                    (SSLSocketFactory) SSLSocketFactory.getDefault();
            SSLSocket socket =
                    (SSLSocket) factory.createSocket(this.dataserverAddress, this.dataserverPort);

            socket.startHandshake();

            PrintWriter out = new PrintWriter(
                    new BufferedWriter(
                            new OutputStreamWriter(
                                    socket.getOutputStream())));

            this.out = out;
            this.socket = socket;
        } catch (Exception ex) {
            System.out.println(ex.getMessage());
            return true;
        }

        return false;
    }

    public boolean sendJsonToDataServer(JSONObject json) throws Exception
    {
        try {
            //TOOD
            out.println(json);
            out.println();
            out.flush();

            if (out.checkError())
                System.out.println(
                        "SSLSocketClient:  java.io.PrintWriter error");

                /* read response */
//            BufferedReader in = new BufferedReader(
//                    new InputStreamReader(
//                            socket.getInputStream()));
//
//            String inputLine;
//            while ((inputLine = in.readLine()) != null)
//                System.out.println(inputLine);

//            in.close();
//            out.close();
//            socket.close();

        } catch (Exception e) {

            return false;
        }

        return true;
    }
}
