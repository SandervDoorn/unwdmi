class Card extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: Math.random(),
            title: 'Temperature',
            icon: 'wi wi-thermometer',
            iconType: 'success',
            value: 17,
            valueType: (<i className="wi wi-celsius"></i>),
            valueDescription: 'Temperature average'
        };

        this.popup = this.popup.bind(this);
    }

    popup(e) {
        let _self = this;
        swal({
            title: _self.state.title,
            type: 'info',
            showCloseButton: true,
            showConfirmButton: false
        });

        ReactDOM.render(this.popupHTML(), document.getElementById('swal2-content'))
    }

    popupHTML() {
        return (
            <div>
                <p className="swal2-description">{ this.state.description }</p>
                <div className="widget-bg-color-icon">
                    <div className="bg-icon bg-icon-success pull-left">
                        <i className={ this.state.icon + ' text-' + this.state.iconType } />
                    </div>
                    <div className="text-right">
                        <h3 className="text-dark p-t-10"><b className="counter">{ this.state.value }</b> { this.state.valueType }</h3>
                        <p className="text-muted mb-0">{ this.state.valueDescription }</p>
                    </div>
                    <div className="clearfix" />
                </div>
            </div>
        )
    }

    render() {
        return (
            <div className="widget-bg-color-icon card-box hoverable" onClick={ this.popup }>
                <div className="bg-icon bg-icon-success pull-left">
                    <i className={ this.state.icon + ' text-' + this.state.iconType } />
                </div>
                <div className="text-right">
                    <h3 className="text-dark m-t-10"><b className="counter">{ this.state.value }</b> { this.state.valueType }</h3>
                    <p className="text-muted mb-0">{ this.state.valueDescription }</p>
                </div>
                <div className="clearfix" />
            </div>
        );
    }
}

window.components = window.components || [];
window.components.Card = Card;