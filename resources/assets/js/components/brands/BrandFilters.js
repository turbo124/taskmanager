import React, { Component } from 'react'
import {
    Row, Col
} from 'reactstrap'
import TableSearch from '../common/TableSearch'
import FilterTile from '../common/FilterTile'

export default class BrandFilters extends Component {
    constructor (props) {
        super(props)
        this.state = {
            dropdownButtonActions: ['download'],
            filters: {
                searchText: ''
            }
        }

        this.filterBrands = this.filterBrands.bind(this)
        this.getFilters = this.getFilters.bind(this)
    }

    filterBrands (event) {
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

        const column = event.target.name
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
    }

    getFilters () {
        return (
            <Row form>
                <Col md={3}>
                    <TableSearch onChange={this.filterBrands}/>
                </Col>
            </Row>
        )
    }

    render () {
        const filters = this.getFilters()

        return (<FilterTile filters={filters}/>)
    }
}
