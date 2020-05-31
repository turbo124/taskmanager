import React, { Component } from 'react'
import {
    FormGroup, Input, Col, Row
} from 'reactstrap'
import CustomerDropdown from '../common/CustomerDropdown'
import TableSearch from '../common/TableSearch'
import FilterTile from '../common/FilterTile'
import DateFilter from '../common/DateFilter'
import CsvImporter from '../common/CsvImporter'
import { translations } from '../common/_icons'
import { consts } from '../common/_consts'

export default class QuoteFilters extends Component {
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
        this.filterInvoices = this.filterInvoices.bind(this)
    }

    filterInvoices (event) {
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
                    <TableSearch onChange={this.filterInvoices}/>
                </Col>

                <Col md={3}>
                    <CustomerDropdown
                        customer={this.props.filters.customer_id}
                        renderErrorFor={this.renderErrorFor}
                        handleInputChanges={this.filterInvoices}
                        customers={this.props.customers}
                        name="customer_id"
                    />
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <Input type='select'
                            onChange={this.filterInvoices}
                            name="status_id"
                            id="status_id"
                        >
                            <option value="">{translations.select_status}</option>
                            <option value="active">{translations.active}</option>
                            <option value='archived'>{translations.archived}</option>
                            <option value='deleted'>{translations.deleted}</option>
                            <option value={consts.quote_status_draft}>{translations.draft}</option>
                            <option value={consts.quote_status_sent}>{translations.sent}</option>
                            <option value='active'>{translations.viewed}</option>
                            <option value={consts.quote_status_approved}>{translations.approved}</option>
                            <option value='overdue'>{translations.expired}</option>
                        </Input>
                    </FormGroup>
                </Col>

                <Col md={1}>
                    <CsvImporter filename="quotes.csv"
                        url={`/api/quote?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}&page=1&per_page=5000`}/>
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <DateFilter onChange={this.filterInvoices} />
                    </FormGroup>
                </Col>
            </Row>
        )
    }

    render () {
        const filters = this.getFilters()

        return (<FilterTile filters={filters}/>)
    }
}
