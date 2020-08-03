import React, { Component } from 'react'
import {
    FormGroup, Input, Row, Col
} from 'reactstrap'
import CustomerDropdown from '../common/CustomerDropdown'
import TableSearch from '../common/TableSearch'
import DateFilter from '../common/DateFilter'
import CsvImporter from '../common/CsvImporter'
import FilterTile from '../common/FilterTile'
import { translations } from '../common/_translations'
import { consts } from '../common/_consts'
import StatusDropdown from '../common/StatusDropdown'

export default class OrderFilters extends Component {
    constructor (props) {
        super(props)
        this.state = {
            dropdownButtonActions: ['download'],
            filters: {
                status_id: 'active',
                customer_id: '',
                searchText: '',
                start_date: '',
                end_date: ''
            }
        }

        this.statuses = [
            {
                value: consts.order_status_draft,
                label: translations.pending
            },
            {
                value: consts.order_status_sent,
                label: translations.sent
            },
            {
                value: consts.order_status_approved,
                label: translations.dispatched
            },
            {
                value: consts.order_status_complete,
                label: translations.complete
            },
            {
                value: consts.order_status_cancelled,
                label: translations.cancelled
            },
            {
                value: consts.order_status_backorder,
                label: translations.backordered
            },
            {
                value: consts.order_status_held,
                label: translations.held
            }
        ]

        this.getFilters = this.getFilters.bind(this)
        this.filterOrders = this.filterOrders.bind(this)
    }

    filterOrders (event) {
        if ('start_date' in event) {
            this.setState(prevState => ({
                filters: {
                    ...prevState.filters,
                    start_date: event.start_date,
                    end_date: event.end_date
                }
            }), () => this.props.filter(this.state.filters))
            return
        }

        const column = event.target.id
        const value = event.target.value

        if (value === 'all') {
            const updatedRowState = this.state.filters.filter(filter => filter.column !== column)
            this.setState({ filters: updatedRowState }, () => this.props.filter(this.state.filters))
            return true
        }

        this.setState(prevState => ({
            filters: {
                ...prevState.filters,
                [column]: value
            }
        }), () => this.props.filter(this.state.filters))

        return true
    }

    getFilters () {
        const { status_id, customer_id, searchText, start_date, end_date } = this.state.filters
        return (
            <Row form>
                <Col md={3}>
                    <TableSearch onChange={this.filterOrders}/>
                </Col>

                <Col sm={12} md={3} className="mt-3 mt-md-0">
                    <CustomerDropdown
                        customer={this.props.filters.customer_id}
                        handleInputChanges={this.filterOrders}
                        customers={this.props.customers}
                        name="customer_id"
                    />
                </Col>

                <Col sm={12} md={2} className="mt-3 mt-md-0">
                    <FormGroup>
                        <StatusDropdown filterStatus={this.filterOrders} statuses={this.statuses}/>
                    </FormGroup>
                </Col>

                <Col sm={12} md={2} className="mt-3 mt-md-0">
                    <FormGroup>
                        <DateFilter onChange={this.filterOrders}/>
                    </FormGroup>
                </Col>

                <Col sm={12} md={1} className="mt-3 mt-md-0">
                    <CsvImporter filename="orders.csv"
                        url={`/api/order?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}&page=1&per_page=5000`}/>
                </Col>
            </Row>
        )
    }

    render () {
        const filters = this.getFilters()

        return (<FilterTile filters={filters}/>)
    }
}
