package Threading;

import DataSaving.DataSaver;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.concurrent.LinkedBlockingQueue;

public class QueueThread extends Thread {
    private LinkedBlockingQueue<JSONObject> queue;
    private DataSaver dataServer;

    /**
     * @param queue
     * @param server
     */
    public QueueThread(LinkedBlockingQueue<JSONObject> queue, DataSaver server)
    {
        this.queue = queue;
        this.dataServer = server;
    }

    @Override
    public void run() {
        super.run();

        while (true) {
            //When the queue reaches a hundred elements, we drain the queue and send the data to the dataserver
            if (this.queue.size() > 100) {
                ArrayList<JSONObject> jsonArray = new ArrayList<>();
                Boolean result = false;

                this.queue.drainTo(jsonArray, 100);

                try {
                    result = this.dataServer.sendJson(jsonArray);
                } catch (Exception ex) {
                    System.out.println(ex.getMessage());
                }

                if (!result) {
                    //If for some reason the data couldnt be transferred to the server,
                    // we put it back in the queue to go for a second time
                    try {
                        this.queue.put(jsonArray.get(0));
                    } catch (Exception ex) {
                        System.out.println(ex.getMessage());
                    }
                }
            }
        }
    }
}
