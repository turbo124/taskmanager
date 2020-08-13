import React, { Component } from 'react'
import axios from 'axios'
import AddGroup from './AddGroup'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import GroupFilters from './GroupFilters'
import GroupItem from './GroupItem'
import { translations } from '../common/_translations'
import Snackbar from '@material-ui/core/Snackbar'

export default class GroupSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: window.innerWidth > 670,
            dropdownButtonActions: ['download'],
            groups: [],
            error: '',
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
        this.filterGroups = this.filterGroups.bind(this)
    }

    addUserToState (groups) {
        const cachedData = !this.state.cachedData.length ? groups : this.state.cachedData
        this.setState({
            groups: groups,
            cachedData: cachedData
        })
    }

    filterGroups (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    handleClose () {
        this.setState({ error: '' })
    }

    userList (props) {
        const { groups } = this.state
        return <GroupItem showCheckboxes={props.showCheckboxes} groups={groups}
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
        const { view, groups, error, isOpen } = this.state
        const fetchUrl = `/api/groups?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <GroupFilters setFilterOpen={this.setFilterOpen.bind(this)} groups={groups}
                                              updateIgnoredColumns={this.updateIgnoredColumns}
                                              filters={this.state.filters} filter={this.filterGroups}
                                              saveBulk={this.saveBulk}
                                              ignoredColumns={this.state.ignoredColumns}/>

                                <AddGroup
                                    groups={groups}
                                    action={this.addUserToState}
                                />
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
                                    entity_type="Group"
                                    bulk_save_url="/api/group/bulk"
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
