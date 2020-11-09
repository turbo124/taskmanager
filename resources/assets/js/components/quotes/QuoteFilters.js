import React, { Component } from 'react'
import { Col, FormGroup, Row } from 'reactstrap'
import CustomerDropdown from '../common/dropdowns/CustomerDropdown'
import TableSearch from '../common/TableSearch'
import FilterTile from '../common/FilterTile'
import DateFilter from '../common/DateFilter'
import CsvImporter from '../common/CsvImporter'
import { translations } from '../utils/_translations'
import { consts } from '../utils/_consts'
import StatusDropdown from '../common/StatusDropdown'

export default class QuoteFilters extends Component {
    constructor (props) {
        super(props)
        this.state = {
            isOpen: false,
            dropdownButtonActions: ['download'],
            filters: {
                status_id: 'active',
                customer_id: '',
                user_id: '',
                project_id: '',
                searchText: '',
                start_date: '',
                end_date: ''
            }
        }

        this.statuses = [
            {
                value: consts.quote_status_draft,
                label: translations.draft
            },
            {
                value: consts.quote_status_sent,
                label: translations.sent
            },
            {
                value: consts.quote_status_approved,
                label: translations.approved
            },
            {
                value: consts.quote_status_invoiced,
                label: translations.invoiced
            },
            {
                value: consts.quote_status_on_order,
                label: translations.on_order
            },
            {
                value: 'overdue',
                label: translations.expired
            }
        ]

        this.getFilters = this.getFilters.bind(this)
        this.filterInvoices = this.filterInvoices.bind(this)
    }

    setFilterOpen (isOpen) {
        this.setState({ isOpen: isOpen })
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

                <Col sm={12} md={3} className="mt-3 mt-md-0">
                    <CustomerDropdown
                        customer={this.props.filters.customer_id}
                        renderErrorFor={this.renderErrorFor}
                        handleInputChanges={this.filterInvoices}
                        customers={this.props.customers}
                        name="customer_id"
                    />
                </Col>

                <Col sm={12} md={2} className="mt-3 mt-md-0">
                    <FormGroup>
                        <StatusDropdown filterStatus={this.filterInvoices} statuses={this.statuses}/>
                    </FormGroup>
                </Col>

                <Col sm={12} md={1} className="mt-3 mt-md-0">
                    <CsvImporter filename="quotes.csv"
                        url={`/api/quote?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}&page=1&per_page=5000`}/>
                </Col>

                <Col sm={12} md={2} className="mt-3 mt-md-0">
                    <FormGroup>
                        <DateFilter onChange={this.filterInvoices}/>
                    </FormGroup>
                </Col>
            </Row>
        )
    }

    render () {
        const filters = this.getFilters()

        return (<FilterTile setFilterOpen={this.props.setFilterOpen} filters={filters}/>)
    }
}
