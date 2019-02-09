class SideBar extends React.Component {
    constructor(props) {
        super(props);

        let _self = this;

        this.state = {
            activities: []
        };

        window.requests.subscribe(function(activity) {
            _self.updateActivities(activity);
        });
    }

    updateActivities(activity)
    {
        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth() + 1; //January is 0!
        let yyyy = today.getFullYear();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        this.state.activities.push({
            text: activity,
            date: yyyy+mm+dd+' '+h+m+s
        });

        this.setState({activities: this.state.activities});
    }

    reverseForIn(obj, f) {
        var arr = [];
        for (var key in obj) {
            // add hasOwnPropertyCheck if needed
            arr.push(key);
        }
        for (var i=arr.length-1; i>=0; i--) {
            f.call(obj, arr[i]);
        }
    }

    render() {
        let activities = this.state.activities;

        return (
            <div className="side-bar right-bar">
                <div className="">
                    <ul className="nav nav-tabs tabs-bordered nav-justified">
                        <li className="nav-item">
                            <a href="#activity" className="nav-link active" data-toggle="tab" aria-expanded="false">
                                Activity
                            </a>
                        </li>
                    </ul>
                    <div className="tab-content">
                        <div className="tab-pane fade show active" id="activity">
                            <div className="timeline-2">
                                { $.map(activities, function(activity, i) {
                                    return (
                                        <div className="time-item" key={i}>
                                            <div className="item-info">
                                                <small className="text-muted">{ moment(activity.date, "YYYYMMDD h:mm:ss").fromNow() }</small>
                                                <p>
                                                    <strong>You</strong> received data from trigger <strong>{ activity.text }</strong>
                                                </p>
                                            </div>
                                        </div>
                                    )
                                }) }
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        );
    }
}

window.components = window.components || [];
window.components.SideBar = SideBar;