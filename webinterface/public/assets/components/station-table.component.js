class StationTable extends React.Component {
    constructor(props)
    {
        super(props);

        let _self = this;

        this.state = {
            stations: []
        };

        props.stations.subscribe(stations => {
            _self.setState({
                stations: stations
            })
        })
    }

    async onRowClick(station)
    {
        swal({
            title: 'Archives?',
            type: 'info',
            html: '<p>Do you want to download the archives from ' + station.name + ' in csv format?</p>' +
            '<button type="button" id="station-table_download-report" class="swal2-styled" style="background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);"><i class="fa fa-download"></i> Day Report</button>' +
            '<button type="button" id="station-table_download-averages" class="swal2-styled" style="background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);"><i class="fa fa-download"></i> Averages</button>',
            showCloseButton: true,
            showConfirmButton: false,
        });

        $('#station-table_download-report').click(async () => {
            let csvContent = 'data:text/csv;charset=utf-8,' + await $.SocketSDK.getArchiveDayReport(station.id);
            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'day-report_' + station.id + '.csv');
            document.body.appendChild(link); // Required for FF

            link.click();
        });

        $('#station-table_download-averages').click(async () => {
            let csvContent = 'data:text/csv;charset=utf-8,' + await $.SocketSDK.getArchiveAverages(station.id);
            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'averages_' + station.id + '.csv');
            document.body.appendChild(link); // Required for FF

            link.click();
        });
    }

    render()
    {
        let options = {
            onRowClick: this.onRowClick
        };

        return (
            <BootstrapTable data={ this.state.stations } striped={true} hover={true} search={ true } options={ options } pagination>
                <TableHeaderColumn dataField="id" isKey={true} dataSort={true}>Station ID</TableHeaderColumn>
                <TableHeaderColumn dataField="name" dataSort={true}>Name</TableHeaderColumn>
                <TableHeaderColumn dataField="temperature" dataSort={true}>Temperature</TableHeaderColumn>
                <TableHeaderColumn dataField="humidity" dataSort={true}>Humidity</TableHeaderColumn>
                <TableHeaderColumn dataField="datetime" dataSort={true}>Datetime</TableHeaderColumn>
            </BootstrapTable>
        );
    }
}

window.components = window.components || [];
window.components.StationTable = StationTable;