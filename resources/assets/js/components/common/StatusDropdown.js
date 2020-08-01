import React, { Component } from 'react'
import { ButtonDropdown, DropdownToggle, DropdownMenu, FormGroup, Input, Label } from 'reactstrap'
import { translations } from './_translations'

export default class StatusDropdown extends Component {
    constructor (props) {
        super(props)
        this.state = {
            allowed_statuses: [],
            dropdownOpen: false
        }

        this.filterStatuses = this.filterStatuses.bind(this)
        this.toggle = this.toggle.bind(this)
    }

    componentDidMount () {
        if (this.props.statuses && this.props.statuses.length) {
            this.props.statuses.push({ value: 'active', label: translations.active })
            this.props.statuses.push({ value: 'active', label: translations.archived })
            this.props.statuses.push({ value: 'active', label: translations.deleted })
        }
    }

    filterStatuses (event) {
        const allowed_statuses = this.state.allowed_statuses
        const check = event.target.checked
        const selected_status = parseInt(event.target.value)

        const e = {}

        if (check) {
            this.setState({
                allowed_statuses: [...this.state.allowed_statuses, selected_status]
            }, () => {
                console.log('status', this.state.allowed_statuses.join(','))
                e.target = {
                    id: 'status_id',
                    value: this.state.allowed_statuses.join(',')
                }
                this.props.filterStatus(e)
            })

            return
        }

        const index = allowed_statuses.indexOf(selected_status)
        if (index > -1) {
            allowed_statuses.splice(index, 1)
            this.setState({
                allowed_statuses: allowed_statuses
            }, () => {
                console.log('status', this.state.allowed_statuses.join(','))
                e.target = {
                    id: 'status_id',
                    value: this.state.allowed_statuses.join(',')
                }

                this.props.filterStatus(e)
            })
        }
    }

    toggle () {
        this.setState({
            dropdownOpen: !this.state.dropdownOpen
        })
    }

    buildDropdownMenu (list) {
        return (
            <ButtonDropdown isOpen={this.state.dropdownOpen} toggle={this.toggle}>
                <DropdownToggle caret>
                    {translations.select_status}
                </DropdownToggle>
                <DropdownMenu>
                    {list}
                </DropdownMenu>
            </ButtonDropdown>
        )
    }

    render () {
        const list = this.props.statuses.map((status, index) => {
            console.log('value', status.value)
            const isChecked = this.state.allowed_statuses.includes(parseInt(status.value))
            return (
                <li className="p-1" style={{ lineHeight: '32px' }} key={index}>
                    <FormGroup check>
                        <Label check>
                            <Input className="mt-2" checked={isChecked} onClick={this.filterStatuses} type="checkbox" value={status.value} />
                            {status.label}
                        </Label>
                    </FormGroup>
                </li>
            )
        })

        return this.buildDropdownMenu(list)
    }
}
