import React, { Component } from 'react'
import {
    FormGroup, Input, Row, Col
} from 'reactstrap'
import CustomerDropdown from '../common/CustomerDropdown'
import TableSearch from '../common/TableSearch'
import DateFilter from '../common/DateFilter'
import CsvImporter from '../common/CsvImporter'
import FilterTile from '../common/FilterTile'
import { translations } from "../common/_icons";
import { consts } from "../common/_consts";

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

                <Col md={3}>
                    <CustomerDropdown
                        customer={this.props.filters.customer_id}
                        handleInputChanges={this.filterOrders}
                        customers={this.props.customers}
                        name="customer_id"
                    />
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <Input type='select'
                            onChange={this.filterOrders}
                            id="status_id"
                            name="status_id"
                        >
                            <option value="">{translations.select_status}</option>
                            <option value="active">{translations.active}</option>
                            <option value="archived">{translations.archived}</option>
                            <option value='deleted'>{translations.deleted}</option>
                            <option value={consts.order_status_draft}>{translations.pending}</option>
                            <option value={consts.order_status_sent}>{translations.sent}</option>
                            <option value={consts.order_status_approved}>{translations.dispatched}</option>
                            <option value={consts.order_status_complete}>{translations.complete}</option>
                        </Input>
                    </FormGroup>
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <DateFilter onChange={this.filterOrders} />
                    </FormGroup>
                </Col>

                <Col md={1}>
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
