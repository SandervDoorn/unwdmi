package Threading;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.*;

public class MultiThreaded {
    public static void main (String[] args)
    {

        try (
                ServerSocket serverSocket = new ServerSocket(20000);
                Socket clientSocket = serverSocket.accept();
                PrintWriter writer = new PrintWriter(clientSocket.getOutputStream(), true);
                BufferedReader in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
        ) {
            String inputLine;

            while ((inputLine = in.readLine()) != null) {
                writer.println(inputLine.toUpperCase());
            }
        } catch (IOException ex) {
            System.out.println(ex.getMessage());

            return;
        }
    }
}
