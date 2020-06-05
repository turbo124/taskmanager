import React, { Component } from 'react'
import {
    FormGroup, Input, Row, Col
} from 'reactstrap'
import TableSearch from '../common/TableSearch'
import CustomerDropdown from '../common/CustomerDropdown'
import CompanyDropdown from '../common/CompanyDropdown'
import DateFilter from '../common/DateFilter'
import CsvImporter from '../common/CsvImporter'
import FilterTile from '../common/FilterTile'
import { translations } from '../common/_icons'
import ExpenseCategoryDropdown from '../common/ExpenseCategoryDropdown'

export default class ExpenseFilters extends Component {
    constructor (props) {
        super(props)
        this.state = {
            dropdownButtonActions: ['download'],
            filters: {
                status_id: 'active',
                searchText: '',
                customer_id: '',
                category_id: '',
                company_id: '',
                start_date: '',
                end_date: ''
            }
        }

        this.filterExpenses = this.filterExpenses.bind(this)
        this.getFilters = this.getFilters.bind(this)
    }

    filterExpenses (event) {
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
        const { searchText, status_id, customer_id, company_id, start_date, end_date } = this.state.filters
        return (
            <Row form>
                <Col md={2}>
                    <TableSearch onChange={this.filterExpenses}/>
                </Col>

                <Col md={3}>
                    <CustomerDropdown
                        customers={this.props.customers}
                        customer={this.props.filters.customer_id}
                        handleInputChanges={this.filterExpenses}
                        name="customer_id"
                    />
                </Col>

                <Col md={3}>
                    <CompanyDropdown
                        companies={this.props.companies}
                        company={this.props.filters.company_id}
                        handleInputChanges={this.filterExpenses}
                        name="company_id"
                    />
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <Input type='select'
                            onChange={this.filterExpenses}
                            id="status_id"
                            name="status_id"
                        >
                            <option value="">{translations.select_status}</option>
                            <option value='active'>{translations.active}</option>
                            <option value='archived'>{translations.archived}</option>
                            <option value='deleted'>{translations.deleted}</option>
                        </Input>
                    </FormGroup>
                </Col>

                <Col>
                    <CsvImporter filename="expenses.csv"
                        url={`/api/expenses?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&company_id=${company_id}&start_date=${start_date}&end_date=${end_date}&page=1&per_page=5000`}/>
                </Col>

                <Col md={2}>
                    <FormGroup>
                        <DateFilter onChange={this.filterExpenses} />
                    </FormGroup>
                </Col>

                <Col md={3}>
                    <FormGroup>
                        <ExpenseCategoryDropdown
                            name="category_id"
                            category={this.props.filters.category_id}
                            renderErrorFor={this.renderErrorFor}
                            onChange={this.filterExpenses}
                        />
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
