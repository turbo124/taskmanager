import React from 'react'
import { Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../utils/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import ExpensePresenter from '../../presenters/ExpensePresenter'
import FormatDate from '../../common/FormatDate'
import FormatMoney from '../../common/FormatMoney'
import { frequencyOptions } from '../../utils/_consts'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    const category = props.categories.length ? props.categories.filter(category => category.id === parseInt(props.entity.expense_category_id)) : []
    const convertedAmount = props.model.convertedAmount
    const customer = props.customers.filter(customer => customer.id === parseInt(props.entity.customer_id))

    const fields = []

    if (props.entity.date.length) {
        fields.date = <FormatDate date={props.entity.date}/>
    }

    if (props.entity.reference_number.length) {
        fields.reference_number = props.entity.reference_number
    }

    if (props.model.isConverted) {
        fields.currency =
            JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === props.model.invoice_currency_id)[0].name
    }

    if (props.entity.exchange_rate.length && props.model.isConverted) {
        fields.exchange_rate = props.entity.exchange_rate
    }

    if (props.entity.payment_date.length) {
        fields.payment_date = <FormatDate date={props.entity.payment_date}/>
    }

    if (category.length) {
        fields.category = category[0].name
    }

    const tax_total = props.model.calculateTaxes(false)

    if (tax_total > 0) {
        fields.tax = <FormatMoney amount={tax_total} customers={this.props.customers}/>
    }

    if (props.entity.custom_value1.length) {
        const label1 = props.model.getCustomFieldLabel('Expense', 'custom_value1')
        fields[label1] = props.model.formatCustomValue(
            'Expense',
            'custom_value1',
            props.entity.custom_value1
        )
    }

    if (props.entity.custom_value2.length) {
        const label2 = props.model.getCustomFieldLabel('Expense', 'custom_value2')
        fields[label2] = props.model.formatCustomValue(
            'Expense',
            'custom_value2',
            props.entity.custom_value2
        )
    }

    if (props.entity.custom_value3.length) {
        const label3 = props.model.getCustomFieldLabel('Expense', 'custom_value3')
        fields[label3] = props.model.formatCustomValue(
            'Expense',
            'custom_value3',
            props.entity.custom_value3
        )
    }

    if (props.entity.custom_value4.length) {
        const label4 = props.model.getCustomFieldLabel('Expense', 'custom_value4')
        fields[label4] = props.model.formatCustomValue(
            'Expense',
            'custom_value4',
            props.entity.custom_value4
        )
    }

    const recurring = []

    if (props.entity.is_recurring === true) {
        if (props.entity.recurring_start_date.length) {
            recurring.start_date = <FormatDate date={props.entity.recurring_start_date}/>
        }

        if (props.entity.recurring_end_date.length) {
            recurring.end_date = <FormatDate date={props.entity.recurring_end_date}/>
        }

        if (props.entity.recurring_due_date.length) {
            recurring.due_date = <FormatDate date={props.entity.recurring_due_date}/>
        }

        if (props.entity.recurring_frequency.length) {
            fields.frequency = translations[frequencyOptions[props.entity.frequency]]
        }
    }

    const header = props.model.isConverted
        ? <ViewEntityHeader heading_1={translations.amount} value_1={props.model.amountWithTax}
            heading_2={translations.converted} value_2={props.model.convertedAmountWithTax}/>
        : <ViewEntityHeader heading_1={translations.amount} value_1={props.model.amountWithTax}/>

    return <React.Fragment>
        {header}

        <ExpensePresenter entity={props.entity} field="status_field"/>

        {!!props.entity.private_notes.length &&
        <Row>
            <InfoMessage icon={icons.lock} message={props.entity.private_notes}/>
        </Row>
        }

        {!!props.entity.public_notes.length &&
        <Row>
            <InfoMessage message={props.entity.public_notes}/>
        </Row>
        }

        <Row>
            <EntityListTile entity={translations.customer} title={customer[0].name}
                icon={icons.customer}/>
        </Row>

        {!!props.user &&
        <Row>
            {props.user}
        </Row>
        }

        <FieldGrid fields={fields}/>

        {!!Object.keys(recurring).length &&
        <div>
            <h5>{translations.recurring}</h5>
            <FieldGrid fields={recurring}/>
        </div>
        }
    </React.Fragment>
}
