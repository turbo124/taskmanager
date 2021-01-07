import React from 'react'
import FormatDate from '../common/FormatDate'

export function getDefaultTableFields () {
    return [
        'name',
        'rate'
    ]
}

export default function TaxRatePresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'due_date':
            return <td><FormatDate date={entity[field]}/></td>
        case 'rate':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}>{`${parseFloat(entity[field]).toFixed(2)}%`}</td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
