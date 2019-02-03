class RegionTable extends React.Component {
    constructor(props) {
        super(props);

        let _self = this;

        this.state = {
            regions: []
        };

        props.regions.subscribe(regions => {
            _self.setState({
                regions: regions
            })
        })
    }

    render() {
        return (
            <BootstrapTable data={ this.state.regions } striped={true} hover={true}>
                <TableHeaderColumn dataField="region" isKey={true} dataSort={true}>Region</TableHeaderColumn>
                <TableHeaderColumn dataField="temperature" dataSort={true}>Temperature</TableHeaderColumn>
                <TableHeaderColumn dataField="humidity" dataSort={true}>Humidity</TableHeaderColumn>
                <TableHeaderColumn dataField="station_count" dataSort={true}>Station Count</TableHeaderColumn>
            </BootstrapTable>
        );
    }
}

window.components = window.components || [];
window.components.RegionTable = RegionTable;