// https://stackoverflow.com/questions/149055/how-to-format-numbers-as-currency-string

import React, { Component } from 'react'
import moment from 'moment'

export default class FormatDate extends Component {
    constructor (props) {
        super(props)
        this.state = {
            invoices: [],
            date_formats: null,
            date_format: ''
        }

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = user_account[0].account.settings
    }

    componentDidMount () {
        this.setState({ date_format: this.settings.date_format })
    }

    render () {
        if (!this.props.date.length) {
            return <span/>
        }

        let date = this.state.date_format.length ? moment(this.props.date).format(this.state.date_format) : moment(this.props.date).format('DD/MMM/YYYY')

        if (this.props.show_date && this.props.show_date === false && this.props.with_time === true) {
            return ` ${moment(this.props.date).format('h:mm:ss A')}`
        }

        if (this.props.with_time && this.props.with_time === true) {
            date += ` ${moment(this.props.date).format('h:mm:ss A')}`
        }

        return date
    }
}

FormatDate.defaultProps = {
    decimal: '.',
    thousands: ',',
    decimalCount: 2,
    symbol: '£'
}
