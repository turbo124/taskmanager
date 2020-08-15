import React from 'react'

export default function TokenPresenter (props) {
    const { field, entity } = props
    const user = props.users.length ? props.users.filter(user => user.id === parseInt(entity.user_id)) : []

    switch (field) {
        case 'token':
            return <td data-label={field}
                onClick={() => props.toggleViewedEntity(entity, entity.name)}>
                {`${entity.token.substring(0, 10)}xxxxxxxxxx`} <br />
                {user.length &&
                `${user[0].first_name} ${user[0].last_name}`
                }

            </td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
