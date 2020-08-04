import React, { Component } from 'react'
import AddTaxRate from './AddTaxRate'
import DataTable from '../common/DataTable'
import {
    Alert,
    Card, CardBody, Row
} from 'reactstrap'
import TaxRateFilters from './TaxRateFilters'
import TaxRateItem from './TaxRateItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class TaxRates extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: window.innerWidth > 670,
            taxRates: [],
            cachedData: [],
            dropdownButtonActions: ['download'],
            filters: {
                status_id: 'active',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            ignoredColumns: [
                'created_at',
                'deleted_at',
                'updated_at'
            ],
            errors: [],
            error: '',
            showRestoreButton: false
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterTaxRates = this.filterTaxRates.bind(this)
    }

    addUserToState (taxRates) {
        const cachedData = !this.state.cachedData.length ? taxRates : this.state.cachedData
        this.setState({
            taxRates: taxRates,
            cachedData: cachedData
        })
    }

    filterTaxRates (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { taxRates } = this.state
        return <TaxRateItem showCheckboxes={props.showCheckboxes} taxRates={taxRates}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    setFilterOpen (isOpen) {
        this.setState({ isOpen: isOpen })
    }

    render () {
        const { taxRates, error, view, filters, isOpen } = this.state
        const { searchText, status_id, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/taxRates?search_term=${searchText}&status=${status_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = <AddTaxRate taxRates={taxRates} action={this.addUserToState}/>
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <TaxRateFilters setFilterOpen={this.setFilterOpen.bind(this)} taxRates={taxRates}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={filters} filter={this.filterTaxRates}
                                    saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                                {addButton}
                            </CardBody>
                        </Card>
                    </div>

                    {error &&
                <Snackbar open={error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                    <Alert severity="danger">
                        {translations.unexpected_error}
                    </Alert>
                </Snackbar>
                    }

                    <div className={margin_class}>
                        <Card>
                            <CardBody>
                                <DataTable
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="Tax Rate"
                                    bulk_save_url="/api/taxRate/bulk"
                                    view={view}
                                    ignore={this.state.ignoredColumns}
                                    disableSorting={['id']}
                                    defaultColumn='name'
                                    userList={this.userList}
                                    fetchUrl={fetchUrl}
                                    updateState={this.addUserToState}
                                />
                            </CardBody>
                        </Card>
                    </div>
                </div>
            </Row>
        )
    }
}
