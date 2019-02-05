class CardPopup extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            description: 'The temperature given in this card is the average temperature of all stations together. The stations who are used in this calculation can be found on the "Stations" page in the side-menu or click <a href="/stations">here</a>.',
            icon: 'wi wi-thermometer',
            iconType: 'success',
            value: 17,
            valueType: (<i className="wi wi-celsius"></i>),
            valueDescription: 'Temperature average'
        };

        this.popup = this.popup.bind(this);
    }

    render() {
        return (
            <div>
                <p style="border-bottom: 1px solid #eee; padding-bottom: 15px">{ this.state.description }</p>
                <div className="widget-bg-color-icon">
                    <div className="bg-icon bg-icon-success pull-left">
                        <i className={ this.state.icon + ' text-' + this.state.iconType }></i>
                    </div>
                    <div className="text-right">
                        <h3 className="text-dark p-t-10"><b className="counter">{ this.state.value }</b> { this.state.valueType }</h3>
                        <p className="text-muted mb-0">{ this.state.valueDescription }</p>
                    </div>
                    <div className="clearfix"></div>
                </div>
            </div>
        );
    }
}

window.components = window.components || [];
window.components.CardPopup = CardPopup;