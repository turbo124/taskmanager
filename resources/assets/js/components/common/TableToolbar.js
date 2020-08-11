import React from 'react'
import BulkActionDropdown from './BulkActionDropdown'
import { icons } from './_icons'

export default function TableToolbar (props) {
    const text_color = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'text-white' : 'text-dark'

    return (
        <div style={{ lineHeight: '32px' }} className="row justify-content-end">
            {props.dropdownButtonActions && <BulkActionDropdown
                dropdownButtonActions={props.dropdownButtonActions}
                saveBulk={props.saveBulk}/>}
            <i onClick={props.fetchEntities} id="refresh" className={`fa ${icons.refresh} ${text_color}`}
                style={{ fontSize: '28px', cursor: 'pointer', marginRight: '6px' }}/>
            <i onClick={props.handleTableActions} id="toggle-checkbox" className={`fa ${icons.checkbox} mr-2`}
                style={{ fontSize: '28px' }}/>
            <i onClick={props.handleTableActions} id="toggle-table" className={`fa ${icons.table} mr-2`}
                style={{ fontSize: '28px' }}/>
            <i onClick={props.handleTableActions} id="toggle-columns" className={`fa ${icons.columns} mr-4`}
                style={{ fontSize: '28px' }}/>
        </div>
    )
}
