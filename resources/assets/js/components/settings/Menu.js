import React, { Component } from 'react'
import { DropdownItem, DropdownMenu, DropdownToggle, UncontrolledDropdown } from 'reactstrap'
import { getSettingsIcon, icons } from '../utils/_icons'
import { translations } from '../utils/_translations'

export default class Menu extends Component {
   render () {
        return (
            <UncontrolledDropdown className="mr-3 pt-2 pl-3">
                <DropdownToggle tag="a" caret>
                    {translations.settings}
                </DropdownToggle>
                <DropdownMenu style={{ height: 'auto', maxHeight: '400px', overflowX: 'hidden' }}>
                    <DropdownItem header>{translations.basic_settings}</DropdownItem>
                    <MenuItem section="account-settings" />
                    <MenuItem section="localisation-settings" />
                    <MenuItem tag="a" href="gateway-settings" />
                    <MenuItem section="tax-rates" />
                    <MenuItem section="product-settings" />
                    <MenuItem section="account-management" />
                    <MenuItem section="device-settings" />
                    <DropdownItem divider/>
                    <DropdownItem header>{translations.advanced_settings}</DropdownItem>
                    <MenuItem section="group-settings" />
                    <MenuItem section="number-settings" />
                    <MenuItem section="field-settings" label={translations.custom_fields} />
                    <MenuItem section="invoice-settings" />
                    <MenuItem section="workflow-settings" />
                    <MenuItem section="portal-settings" label={translations.customer_portal} />
                    <MenuItem section="email-settings" />
                    <MenuItem section="template-settings" />
                    <DropdownItem tag="a" href="/#/users"><i className={`fa ${icons.user}`}/>{translations.users}
                    </DropdownItem>
                </DropdownMenu>
            </UncontrolledDropdown>
        )
    }
}

export class MenuItem extends Component {
    constructor (props) {
        super(props)
        this.state = {
            is_mobile: window.innerWidth <= 768,
        }

        this.handleWindowSizeChange = this.handleWindowSizeChange.bind(this)
    }

    componentWillMount () {
        window.addEventListener('resize', this.handleWindowSizeChange)
    }

    // make sure to remove the listener
    // when the component is not mounted anymore
    componentWillUnmount () {
        window.removeEventListener('resize', this.handleWindowSizeChange)
    }

    handleWindowSizeChange () {
        this.setState({ is_mobile: window.innerWidth <= 768 })
    }

    render () {
        const label = this.props.section.replace("-", "_")
        let icon = null;

        if(this.props.section === 'device-settings') {
            icon = this.state.is_mobile ? 'fa-mobile' : 'fa-desktop'
        } else {
            getSettingsIcon(this.props.section)
        }

        return (
           <DropdownItem tag="a" href={`/#/${this.props.section}`}><i
               className={`fa ${icon}`}/>{label}
           </DropdownItem>
        )
    }
}
