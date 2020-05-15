import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditToken from './EditToken'
import { Input } from 'reactstrap'

export default class TokenItem extends Component {
    constructor (props) {
        super(props)

        this.deleteToken = this.deleteToken.bind(this)
    }

    deleteToken (id, archive = false) {
        const url = archive === true ? `/api/tokens/archive/${id}` : `/api/tokens/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrTokens = [...self.props.tokens]
                const index = arrTokens.findIndex(token => token.id === id)
                arrTokens.splice(index, 1)
                self.props.addUserToState(arrTokens)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { tokens, ignoredColumns } = this.props
        if (tokens && tokens.length) {
            return tokens.map(token => {
                const restoreButton = token.deleted_at
                    ? <RestoreModal id={token.id} entities={tokens} updateState={this.props.addUserToState}
                        url={`/api/tokens/restore/${token.id}`}/> : null
                const deleteButton = !token.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteToken} id={token.id}/> : null
                const archiveButton = !token.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteToken} id={token.id}/> : null

                const editButton = !token.deleted_at ? <EditToken
                    tokens={tokens}
                    token={token}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(token).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td onClick={() => this.props.toggleViewedEntity(token, token.name)} data-label={key}
                        key={key}>{token[key]}</td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const selectedRow = this.props.viewId === token.id ? 'bg-warning text-dark' : ''

                return <tr className={selectedRow} key={token.id}>
                    <td>
                        <Input className={checkboxClass} value={token.id} type="checkbox" onChange={this.props.onChangeBulk}/>
                        <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                            restore={restoreButton}/>
                    </td>
                    {columnList}
                </tr>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
