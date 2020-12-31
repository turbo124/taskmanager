import React, { Component } from 'react'
import axios from 'axios'
import { Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditQuote from './edit/EditQuote'
import QuotePresenter from '../presenters/QuotePresenter'

export default class QuoteItem extends Component {
    constructor (props) {
        super(props)

        this.deleteQuote = this.deleteQuote.bind(this)
    }

    deleteQuote (id, archive = false) {
        const url = archive === true ? `/api/quote/archive/${id}` : `/api/quote/${id}`
        const self = this
        axios.delete(url).then(function (response) {
            const arrQuotes = [...self.props.quotes]
            const index = arrQuotes.findIndex(payment => payment.id === id)
            arrQuotes.splice(index, 1)
            self.props.updateInvoice(arrQuotes)
        })
            .catch(function (error) {
                self.setState(
                    {
                        error: error.response.data
                    }
                )
            })
    }

    render () {
        const { quotes, custom_fields, customers } = this.props
        if (this.props.quotes && this.props.quotes.length && customers.length) {
            return quotes.map((quote, index) => {
                const restoreButton = quote.deleted_at && !quote.is_deleted
                    ? <RestoreModal id={quote.id} entities={quotes} updateState={this.props.updateInvoice}
                        url={`/api/quotes/restore/${quote.id}`}/> : null

                const deleteButton = !quote.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteQuote} id={quote.id}/> : null

                const archiveButton = !quote.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteQuote} id={quote.id}/> : null

                const editButton = !quote.deleted_at ? <EditQuote
                    custom_fields={custom_fields}
                    customers={customers}
                    modal={true}
                    add={false}
                    invoice={quote}
                    invoice_id={quote.id}
                    action={this.props.updateInvoice}
                    invoices={quotes}
                /> : null

                const columnList = Object.keys(quote).filter(key => {
                    return this.props.ignoredColumns && !this.props.ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(quote, quote.number, editButton)}
                        data-label={key}><QuotePresenter customers={customers} edit={editButton}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field={key} entity={quote}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(quote.id)
                const selectedRow = this.props.viewId === quote.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return !this.props.show_list ? (
                    <tr className={selectedRow} key={index}>
                        <td>
                            {!!this.props.onChangeBulk &&
                            <Input checked={isChecked} className={checkboxClass} value={quote.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            }
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr>
                ) : <ListGroupItem onClick={() => this.props.toggleViewedEntity(quote, quote.number, editButton)}
                    key={index}
                    className="list-group-item-dark list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">{<QuotePresenter customers={customers} field="customer_id"
                            entity={quote}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>}</h5>
                        {<QuotePresenter customers={customers}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field="balance" entity={quote} edit={editButton}/>}
                    </div>
                    <div className="d-flex w-100 justify-content-between">
                        <span className="mb-1 text-muted">{quote.number} . {<QuotePresenter field="due_date"
                            entity={quote}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>} </span>
                        <span>{<QuotePresenter field="status_field" entity={quote}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>}</span>
                    </div>
                    {actionMenu}
                </ListGroupItem>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
