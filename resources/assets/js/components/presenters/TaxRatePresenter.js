import React from 'react'

export default function TaxRatePresenter (props) {
    const { field, entity } = props

    switch (field) {
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
