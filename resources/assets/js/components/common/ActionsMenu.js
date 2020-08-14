import React, { Component } from 'react'
import { Dropdown, DropdownMenu, DropdownToggle, UncontrolledTooltip } from 'reactstrap'
import { icons } from './_icons'
import { translations } from './_translations'

export default class ActionsMenu extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: false
        }

        this.toggle = this.toggle.bind(this)
    }

    toggle () {
        this.setState({ isOpen: !this.state.isOpen })
    }

    render () {
        return (
            <React.Fragment>
                <UncontrolledTooltip placement="right" target="actionsTooltip">
                    {translations.action}
                </UncontrolledTooltip>

                <Dropdown isOpen={this.state.isOpen} toggle={this.toggle}>
                    <DropdownToggle className="menu-button">
                        <i id="actionsTooltip" className={`fa ${icons.ellipsis}`} aria-hidden="true" type="ellipsis"/>
                    </DropdownToggle>
                    <DropdownMenu persist={true}>
                        {this.props.edit}
                        {this.props.delete}
                        {this.props.restore}
                        {this.props.archive}
                        {this.props.refund}
                    </DropdownMenu>
                </Dropdown>
            </React.Fragment>
        )
    }
}
