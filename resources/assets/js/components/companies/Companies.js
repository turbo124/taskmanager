import React, { Component } from 'react'
import axios from 'axios'
import AddCompany from './AddCompany'
import DataTable from '../common/DataTable'
import {
    Alert,
    Card,
    CardBody,
    Row
} from 'reactstrap'
import CompanyFilters from './CompanyFilters'
import CompanyItem from './CompanyItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Companies extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: window.innerWidth > 670,
            users: [],
            brands: [],
            bulk: [],
            cachedData: [],
            errors: [],
            dropdownButtonActions: ['download'],
            error: '',
            view: {
                ignore: ['assigned_to', 'country_id', 'currency_id', 'industry_id', 'user_id'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            filters: {
                status_id: 'active',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            custom_fields: [],
            ignoredColumns: [
                'contacts',
                'deleted_at',
                'created_at',
                'address_1',
                'company_logo',
                'address_2',
                'postcode',
                'town',
                'city',
                'token',
                'currency_id',
                'industry_id',
                'country_id',
                'user_id',
                'assigned_to',
                'private_notes',
                'custom_value1',
                'custom_value2',
                'custom_value3',
                'custom_value4'
            ],
            showRestoreButton: false
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterCompanies = this.filterCompanies.bind(this)
    }

    componentDidMount () {
        this.getUsers()
        this.getCustomFields()
    }

    addUserToState (brands) {
        this.setState({ brands: brands })
    }

    handleClose () {
        this.setState({ error: '' })
    }

    filterCompanies (filters) {
        this.setState({ filters: filters })
    }

    userList (props) {
        const { brands, custom_fields, users } = this.state
        return <CompanyItem showCheckboxes={props.showCheckboxes} brands={brands} users={users}
            custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            viewId={props.viewId}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    getCustomFields () {
        axios.get('api/accounts/fields/Company')
            .then((r) => {
                this.setState({
                    custom_fields: r.data.fields
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    getUsers () {
        axios.get('api/users')
            .then((r) => {
                this.setState({
                    users: r.data
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    setFilterOpen (isOpen) {
        this.setState({ isOpen: isOpen })
    }

    render () {
        const { custom_fields, users, error, view, brands, isOpen } = this.state
        const { searchText, status_id, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/companies?search_term=${searchText}&status=${status_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = users.length
            ? <AddCompany brands={brands} users={users} action={this.addUserToState}
                custom_fields={custom_fields}/> : null
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <CompanyFilters setFilterOpen={this.setFilterOpen.bind(this)} brands={brands} updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={this.state.filters} filter={this.filterCompanies}
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
                                    entity_type="Company"
                                    bulk_save_url="/api/company/bulk"
                                    view={view}
                                    disableSorting={['id']}
                                    defaultColumn='name'
                                    ignore={this.state.ignoredColumns}
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
