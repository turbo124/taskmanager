import React, { Component } from 'react'
import axios from 'axios'
import AddPaymentTerm from './AddPaymentTerm'
import { CardBody, Card, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import PaymentTermFilters from './PaymentTermFilters'
import PaymentTermItem from './PaymentTermItem'

export default class PaymentTerms extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: window.innerWidth > 670,
            error: '',
            dropdownButtonActions: ['download'],
            paymentTerms: [],
            cachedData: [],
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['settings', 'deleted_at', 'created_at'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterPaymentTerms = this.filterPaymentTerms.bind(this)
    }

    addUserToState (paymentTerms) {
        const cachedData = !this.state.cachedData.length ? paymentTerms : this.state.cachedData
        this.setState({
            paymentTerms: paymentTerms,
            cachedData: cachedData
        })
    }

    filterPaymentTerms (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    handleClose () {
        this.setState({ error: '' })
    }

    userList (props) {
        const { paymentTerms } = this.state
        return <PaymentTermItem showCheckboxes={props.showCheckboxes} paymentTerms ={paymentTerms}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            viewId={props.viewId}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
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
        const { searchText, status, start_date, end_date } = this.state.filters
        const { view, paymentTerms, error, isOpen } = this.state
        const fetchUrl = `/api/payment_terms?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <PaymentTermFilters setFilterOpen={this.setFilterOpen.bind(this)} paymentTerms = {paymentTerms}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={this.state.filters} filter={this.filterPaymentTerms}
                                    saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                                <AddPaymentTerm
                                    payment_terms ={paymentTerms}
                                    action={this.addUserToState}
                                />
                            </CardBody>
                        </Card>
                    </div>

                    {error && <div className="alert alert-danger" role="alert">
                        {error}
                    </div>}

                    <div className={margin_class}>
                        <Card>
                            <CardBody>
                                <DataTable
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="Group"
                                    bulk_save_url="/api/payment_terms/bulk"
                                    view={view}
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
