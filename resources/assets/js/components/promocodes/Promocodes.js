import React, { Component } from 'react'
import axios from 'axios'
import AddPromocode from './AddPromocode'
import { CardBody, Card, Alert } from 'reactstrap'
import DataTable from '../common/DataTable'
import PromocodeFilters from './PromocodeFilters'
import PromocodeItem from './PromocodeItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Promocodes extends Component {
    constructor (props) {
        super(props)

        this.state = {
            error: '',
            dropdownButtonActions: ['download'],
            promocodes: [],
            cachedData: [],
            view: {
                ignore: ['values'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['data'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterPromocodes = this.filterPromocodes.bind(this)
    }

    addUserToState (promocodes) {
        const cachedData = !this.state.cachedData.length ? promocodes : this.state.cachedData
        this.setState({
            promocodes: promocodes,
            cachedData: cachedData
        })
    }

    filterPromocodes (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { promocodes } = this.state
        console.log('promocodes', promocodes)
        return <PromocodeItem showCheckboxes={props.showCheckboxes} promocodes={promocodes}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
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

    render () {
        const { searchText, status, start_date, end_date, error } = this.state.filters
        const { view, promocodes } = this.state
        const fetchUrl = `/api/promocodes?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `

        return (
            <React.Fragment>
                <div className="topbar">
                    <Card>
                        <CardBody>
                            <PromocodeFilters promocodes={promocodes}
                                updateIgnoredColumns={this.updateIgnoredColumns}
                                filters={this.state.filters} filter={this.filterPromocodes}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                            <AddPromocode
                                promocodes={promocodes}
                                action={this.addUserToState}
                            />
                        </CardBody>
                    </Card>
                </div>

                {error &&
                <Snackbar open={this.state.error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                    <Alert severity="danger">
                        {translations.unexpected_error}
                    </Alert>
                </Snackbar>
                }

                <div className="fixed-margin-datatable fixed-margin-datatable-mobile">
                    <Card>
                        <CardBody>
                            <DataTable
                                dropdownButtonActions={this.state.dropdownButtonActions}
                                entity_type="Promocode"
                                bulk_save_url="/api/promocodes/bulk"
                                view={view}
                                ignore={this.state.ignoredColumns}
                                userList={this.userList}
                                fetchUrl={fetchUrl}
                                updateState={this.addUserToState}
                            />
                        </CardBody>
                    </Card>
                </div>
            </React.Fragment>
        )
    }
}
