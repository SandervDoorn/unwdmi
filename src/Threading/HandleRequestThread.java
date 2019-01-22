package Threading;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.Socket;

public class HandleRequestThread implements Runnable {
    private String input;
    private PrintWriter writer;
    private BufferedReader in;

    public HandleRequestThread(Socket clientSocket) throws IOException {
        this.in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
        this.writer = new PrintWriter(clientSocket.getOutputStream(), false);
    }

    @Override
    public void run() {
        try {
            char[] buffer = new char[2048];
            int test;
            while ((test = this.in.read()) != -1) {
                System.out.println(test);
            }
            while ((this.input = this.in.readLine()) != null) {
                this.writer.println(this.input.toUpperCase());
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
