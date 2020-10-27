import React, { Component } from 'react'
import axios from 'axios'
import AddLead from './edit/AddLeadForm'
import DataTable from '../common/DataTable'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import queryString from 'query-string'
import LeadFilters from './LeadFilters'
import LeadItem from './LeadItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../utils/_translations'
import UserRepository from '../repositories/UserRepository'

export default class Leads extends Component {
    constructor ( props ) {
        super ( props )

        this.state = {
            isOpen: window.innerWidth > 670,
            leads: [],
            cachedData: [],
            errors: [],
            bulk: [],
            dropdownButtonActions: ['download'],
            error: '',
            show_success: false,
            error_message: translations.unexpected_error,
            success_message: translations.success_message,
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            filters: {
                status_id: 'active',
                customer_id: queryString.parse ( this.props.location.search ).customer_id || '',
                user_id: queryString.parse ( this.props.location.search ).user_id || '',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            custom_fields: [],
            ignoredColumns: [
                'industry_id',
                'emails',
                'created_at',
                'deleted_at',
                'updated_at',
                'address_1',
                'address_2',
                'is_deleted',
                'archived_at',
                'account_id',
                'custom_value1',
                'custom_value2',
                'custom_value3',
                'custom_value4',
                'city',
                'zip',
                'source_type',
                'valued_at',
                'company_name',
                'job_title',
                'website',
                'private_notes',
                'public_notes',
                'user_id',
                'assigned_to',
                'task_status',
                'id'
            ],
            showRestoreButton: false
        }

        this.addUserToState = this.addUserToState.bind ( this )
        this.userList = this.userList.bind ( this )
        this.filterLeads = this.filterLeads.bind ( this )
    }

    componentDidMount () {
        this.getUsers ()
        this.getCustomFields ()
    }

    addUserToState ( leads ) {
        const cachedData = !this.state.cachedData.length ? leads : this.state.cachedData
        this.setState ( {
            leads: leads,
            cachedData: cachedData
        } )
    }

    filterLeads ( filters ) {
        this.setState ( { filters: filters } )
    }

    handleClose () {
        this.setState ( { error: '', show_success: false } )
    }

    userList ( props ) {
        const { leads, custom_fields, users } = this.state
        return <LeadItem showCheckboxes={props.showCheckboxes} leads={leads} users={users} custom_fields={custom_fields}
                         viewId={props.viewId}
                         ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
                         toggleViewedEntity={props.toggleViewedEntity}
                         bulk={props.bulk}
                         onChangeBulk={props.onChangeBulk}/>
    }

    getCustomFields () {
        axios.get ( 'api/accounts/fields/Lead' )
            .then ( ( r ) => {
                this.setState ( {
                    custom_fields: r.data.fields
                } )
            } )
            .catch ( ( e ) => {
                this.setState ( {
                    loading: false,
                    error: e
                } )
            } )
    }

    getUsers () {
        const userRepository = new UserRepository ()
        userRepository.get ().then ( response => {
            if ( !response ) {
                alert ( 'error' )
            }

            this.setState ( { users: response }, () => {
                console.log ( 'users', this.state.users )
            } )
        } )
    }

    setFilterOpen ( isOpen ) {
        this.setState ( { isOpen: isOpen } )
    }

    setError ( message = null ) {
        this.setState ( { error: true, error_message: message === null ? translations.unexpected_error : message } )
    }

    setSuccess ( message = null ) {
        this.setState ( {
            show_success: true,
            success_message: message === null ? translations.success_message : message
        } )
    }

    render () {
        const { leads, users, custom_fields, ignoredColumns, view, isOpen, error_message, success_message, show_success } = this.state
        const { status_id, searchText, start_date, end_date, user_id } = this.state.filters
        const fetchUrl = `/api/leads?search_term=${searchText}&user_id=${user_id}&status=${status_id}&start_date=${start_date}&end_date=${end_date}`
        const { error } = this.state
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call ( localStorage, 'datatable_collapsed' ) && localStorage.getItem ( 'datatable_collapsed' ) === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <LeadFilters setFilterOpen={this.setFilterOpen.bind ( this )} leads={leads}
                                             updateIgnoredColumns={this.updateIgnoredColumns}
                                             filters={this.state.filters} filter={this.filterLeads}
                                             saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                                <AddLead users={users} leads={leads} action={this.addUserToState}
                                         custom_fields={custom_fields}/>
                            </CardBody>
                        </Card>
                    </div>

                    {error &&
                    <Snackbar open={error} autoHideDuration={3000} onClose={this.handleClose.bind ( this )}>
                        <Alert severity="danger">
                            {error_message}
                        </Alert>
                    </Snackbar>
                    }

                    {show_success &&
                    <Snackbar open={show_success} autoHideDuration={3000} onClose={this.handleClose.bind ( this )}>
                        <Alert severity="success">
                            {success_message}
                        </Alert>
                    </Snackbar>
                    }

                    <div className={margin_class}>
                        <Card>
                            <CardBody>
                                <DataTable
                                    setSuccess={this.setSuccess.bind ( this )}
                                    setError={this.setError.bind ( this )}
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="Lead"
                                    bulk_save_url="/api/lead/bulk"
                                    view={view}
                                    disableSorting={['id']}
                                    defaultColumn='name'
                                    ignore={ignoredColumns}
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
