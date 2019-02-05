class Card extends React.Component {
    constructor(props) {
        super(props);

        let _self = this;

        this.state = {
            id: Math.random(),
            title: props.title,
            description: props.description,
            icon: props.icon,
            iconType: props.iconType,
            valueType: props.valueType,
            valueDescription: props.valueDescription
        };

        props.value.subscribe(val => {
            _self.setState({
                value: val
            })
        });

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
        $('#swal2-content').show()
    }

    popupHTML() {
        return (
            <div>
                { this.state.description }
                <div className="widget-bg-color-icon">
                    <div className={ 'bg-icon bg-icon-' + this.state.iconType + ' pull-left' }>
                        <i className={ this.state.icon + ' text-' + this.state.iconType } />
                    </div>
                    <div className="text-right">
                        <h3 className="text-dark p-t-10"><b>{ this.state.value }</b> { this.state.valueType }</h3>
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
                <div className={ 'bg-icon bg-icon-' + this.state.iconType + ' pull-left' }>
                    <i className={ this.state.icon + ' text-' + this.state.iconType } />
                </div>
                <div className="text-right">
                    <h3 className="text-dark m-t-10"><b>{ this.state.value }</b> { this.state.valueType }</h3>
                    <p className="text-muted mb-0">{ this.state.valueDescription }</p>
                </div>
                <div className="clearfix" />
            </div>
        );
    }
}

window.components = window.components || [];
window.components.Card = Card;