import React, { Component } from 'react'
import CustomerDropdown from '../common/CustomerDropdown'
import {
    FormGroup, Input, Col, Row
} from 'reactstrap'
import TableSearch from '../common/TableSearch'
import FilterTile from '../common/FilterTile'
import DateFilter from '../common/DateFilter'
import CsvImporter from '../common/CsvImporter'
import { translations } from '../common/_icons'
import { consts } from '../common/_consts'

export default class CreditFilters extends Component {
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

        this.filterCredits = this.filterCredits.bind(this)
        this.getFilters = this.getFilters.bind(this)
    }

    filterCredits (event) {
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
        return (
            <Row form>
                <Col md={3}>
                    <TableSearch onChange={this.filterCredits}/>
                </Col>

                <Col md={3}>
                    <CustomerDropdown
                        customer={this.props.filters.customer_id}
                        handleInputChanges={this.filterCredits}
                        customers={this.props.customers}
                        name="customer_id"
                    />
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <Input type='select'
                            onChange={this.filterCredits}
                            id="status_id"
                            name="status_id"
                        >
                            <option value="">{translations.select_status}</option>
                            <option value='active'>{translations.active}</option>
                            <option value='archived'>{translations.archived}</option>
                            <option value='deleted'>{translations.deleted}</option>
                            <option value={consts.credit_status_draft}>{translations.draft}</option>
                            <option value={consts.credit_status_sent}>{translations.sent}</option>
                            <option value='3'>{translations.partial}</option>
                            <option value='4'>{translations.applied}</option>
                        </Input>
                    </FormGroup>
                </Col>

                <Col md={1}>
                    <CsvImporter filename="credits.csv"
                        url={`/api/credits?status=${this.state.filters.status_id}&customer_id=${this.state.filters.customer_id} &start_date=${this.state.filters.start_date}&end_date=${this.state.filters.end_date}&page=1&per_page=5000`}/>
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <DateFilter onChange={this.filterCredits}/>
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
