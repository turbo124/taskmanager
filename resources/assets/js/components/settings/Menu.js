import React, { Component } from 'react'
import { DropdownItem, DropdownMenu, DropdownToggle, UncontrolledDropdown } from 'reactstrap'
import { getSettingsIcon, icons } from '../utils/_icons'
import { translations } from '../utils/_translations'

export default class Menu extends Component {
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
        return (
            <UncontrolledDropdown className="mr-3 pt-2 pl-3">
                <DropdownToggle tag="a" caret>
                    {translations.settings}
                </DropdownToggle>
                <DropdownMenu style={{ height: 'auto', maxHeight: '400px', overflowX: 'hidden' }}>
                    <DropdownItem header>{translations.basic_settings}</DropdownItem>
                    <DropdownItem tag="a" href="/#/accounts"><i
                        className={`fa ${getSettingsIcon('accounts')}`}/>{translations.account_details}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/localisation"><i
                        className={`fa ${getSettingsIcon('localisation')}`}/>{translations.localisation_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/gateway-settings"><i
                        className={`fa ${getSettingsIcon('gateway-settings')}`}/>{translations.online_payments}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/tax-rates"><i
                        className={`fa ${getSettingsIcon('tax-rates')}`}/>{translations.tax_rates}</DropdownItem>
                    <DropdownItem tag="a" href="/#/product-settings"><i
                        className={`fa ${getSettingsIcon('product-settings')}`}/>{translations.product_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/modules"><i
                        className={`fa ${getSettingsIcon('modules')}`}/>{translations.account_management}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/device-settings"><i
                        className={`fa ${this.state.is_mobile ? 'fa-mobile' : 'fa-desktop'}`}/>{translations.device_settings}
                    </DropdownItem>
                    <DropdownItem divider/>
                    <DropdownItem header>{translations.advanced_settings}</DropdownItem>
                    <DropdownItem tag="a" href="/#/group-settings"><i
                        className={`fa ${getSettingsIcon('group-settings')}`}/>{translations.group_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/number-settings"><i
                        className={`fa ${getSettingsIcon('number-settings')}`}/>{translations.number_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/field-settings"><i
                        className={`fa ${getSettingsIcon('field-settings')}`}/>{translations.custom_fields}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/invoice-settings"><i
                        className={`fa ${getSettingsIcon('invoice-settings')}`}/>{translations.invoice_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/workflow-settings"><i
                        className={`fa ${getSettingsIcon('workflow-settings')}`}/>{translations.workflow_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/portal-settings"><i
                        className={`fa ${getSettingsIcon('portal-settings')}`}/>{translations.customer_portal}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/email-settings"><i
                        className={`fa ${getSettingsIcon('email-settings')}`}/>{translations.email_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/template-settings"><i
                        className={`fa ${getSettingsIcon('template-settings')}`}/>{translations.template_settings}
                    </DropdownItem>
                    <DropdownItem tag="a" href="/#/users"><i className={`fa ${icons.user}`}/>{translations.users}
                    </DropdownItem>
                </DropdownMenu>
            </UncontrolledDropdown>
        )
    }
}
