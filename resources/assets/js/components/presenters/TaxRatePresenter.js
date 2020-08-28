import React from 'react'
import FormatDate from '../common/FormatDate'

export default function TaxRatePresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'due_date':
            return <td><FormatDate date={entity[field]}/></td>
        case 'rate':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}>{`${parseFloat(entity[field]).toFixed(2)}%`}</td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
