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
            html: '<p>Do you want to download the archives from ' + station.name + ' in csv here?</p>',
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: '<i class="fa fa-archive" /> Download'
        }).then(async (result) => {
            let csvContent = 'data:text/csv;charset=utf-8,' + await $.SocketSDK.getArchive(station.id);
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'station-' + station.id + '.csv');
            document.body.appendChild(link); // Required for FF

            link.click();
        })

        //await $.SocketSDK.getArchive(station.id)
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